<?php namespace Riari\Forum\Models;

use Riari\Forum\Support\Traits\CachesData;

class ForumNotification extends BaseModel
{
    use CachesData;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
    protected $table = 'forum_notifications';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = ['post_id', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
