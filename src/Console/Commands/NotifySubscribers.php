<?php namespace Riari\Forum\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Riari\Forum\Mail\NewPostCreated;
use Riari\Forum\Models\Category;
use Riari\Forum\Models\ForumNotification;
use Riari\Forum\Models\Post;
use Riari\Forum\Models\Thread;
use Riari\Forum\Support\Stats;

class NotifySubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forum:notify-subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify subscribers via email when there is a new/updated post.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pendingNotifications = $this->getPendingNotifications();

        $pendingNotifications->each(function ($notification) {
            $notification->status = 'processing';
            // $notification->save();

            $post = $notification->post;
            $subscriptions = $post->thread->subscription;

            $subscriptions->each(function ($subscription) use ($post) {
                if ($post->author_id !== $subscription->user_id) {
                    Mail::to($subscription->user)->send(new NewPostCreated($post, $subscription->user));
                }
            });

            // $notification->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getPendingNotifications()
    {
        return (new ForumNotification())->newQuery()
            ->where('status', 'pending')
            ->limit(10)
            ->get();
    }
}
