<?php namespace Riari\Forum\Models\Observers;

use Carbon\Carbon;
use Riari\Forum\Models\ForumNotification;
use Riari\Forum\Models\ForumSubscription;
use Riari\Forum\Models\Thread;
use Riari\Forum\Support\Stats;

class PostObserver
{
    public function created($post)
    {
        // Update the thread's updated_at timestamp to match the created_at timestamp of the new post
        $post->thread->updated_at = $post->created_at;
        $post->thread->save();

        // Set the post's sequence number
        $post->sequence = $post->getSequenceNumber();
        $post->saveWithoutTouch();

        // Update the thread reply count if this is not the first post in the thread
        if ($post->thread->posts->count() > 1) {
            $post->thread->reply_count += 1;
            $post->thread->saveWithoutTouch();
        }

        // Update the post count on the category
        $category = $post->thread->category;
        $category->post_count += 1;
        $category->saveWithoutTouch();

        // Prepare for notification
        ForumNotification::create([
            'post_id' => $post->id,
            'status' => 'pending',
        ]);

        // Subscribe thread author if not subscribe.
        if ($post->thread->author_id === $post->author_id) {
            $subscription = (new ForumSubscription())->newQuery()
                ->where('subscribable_id', $post->thread_id)
                ->where('subscribable_type', Thread::class)
                ->where('user_id', $post->author_id)
                ->first();

            if (!$subscription) {
                ForumSubscription::create([
                    'subscribable_id' => $post->thread_id,
                    'subscribable_type' => Thread::class,
                    'user_id' => $post->author_id,
                ]);
            }
        }
    }

    public function deleted($post)
    {
        if (!is_null($post->children)) {
            // Other posts reference this one, so set their parent post IDs to 0
            $post->children()->update(['post_id' => 0]);
        }

        if ($post->thread->posts->isEmpty()) {
            // The containing thread is now empty, so delete the thread accordingly
            if (!$post->deleted_at || $post->deleted_at->toDateTimeString() !== Carbon::now()->toDateTimeString()) {
                // The post was force-deleted, so the thread should be too
                $post->thread()->withTrashed()->forceDelete();
            } else {
                // The post was soft-deleted, so just soft-delete the thread
                $post->thread()->delete();
            }
        }

        // Update sequence numbers for all of the thread's posts
        $post->thread->posts->each(function ($post) {
            $post->sequence = $post->getSequenceNumber();
            $post->saveWithoutTouch();
        });

        // Update the thread's updated_at timestamp to match the created_at timestamp of its latest post
        $post->thread->updated_at = $post->thread->lastPostTime;
        $post->thread->save();

        Stats::updateThread($post->thread);
        Stats::updateCategory($post->thread->category);
    }

    public function restored($post)
    {
        if (is_null($post->thread->posts)) {
            // The containing thread was soft-deleted, so restore that too
            $post->thread()->withTrashed()->restore();
        }

        Stats::updateThread($post->thread);
        Stats::updateCategory($post->thread->category);
    }
}
