<?php namespace Riari\Forum\Models;

use Illuminate\Support\Facades\Gate;
use Riari\Forum\Support\Traits\CachesData;

class ForumSubscription extends BaseModel
{
    use CachesData;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
    protected $table = 'forum_subscriptions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = ['user_id', 'subscribable_id', 'subscribable_type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subscribable()
    {
        return $this->morphTo();
    }
}
