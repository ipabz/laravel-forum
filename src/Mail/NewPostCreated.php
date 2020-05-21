<?php

namespace Riari\Forum\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Riari\Forum\Models\ForumSubscription;
use Riari\Forum\Models\Post;

class NewPostCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Post
     */
    public $post;

    /**
     * @var User
     */
    public $user;

    /**
     * @var ForumSubscription
     */
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     * @param User $user
     * @param ForumSubscription $subscription
     */
    public function __construct(Post $post, User $user, $subscription)
    {
        $this->post = $post;
        $this->user = $user;
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('NAESA - New Post')
            ->markdown('forum::emails.new_post');
    }
}
