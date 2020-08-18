<?php namespace Riari\Forum\Models\Traits;

trait HasAuthor
{
    /**
     * Relationship: Author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        $model = config('forum.integration.user_model');
        if (method_exists($model, 'withTrashed')) {
            return $this->belongsTo($model, 'author_id')->withTrashed();
        }

        return $this->belongsTo($model, 'author_id');
    }

    /**
     * Attribute: Author name.
     *
     * @return mixed
     */
    public function getAuthorNameAttribute()
    {
        $attribute = config('forum.integration.user_name');

        $author = null;

        if (!is_null($this->author)) {
            $author = $this->author->$attribute;
        }

        if($this->anonymous) {
            $text = 'Anonymous';
            $text .= (auth()->user() && auth()->user()->is_admin) ? '<br/>(' . $author . ')' : '';
        } else {
            $text = $author;
        }

        return $text;
    }
}
