<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Facades\Redis;
use Laravel\Scout\Searchable;



class Thread extends Model
{
    use Searchable;
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribedTo'];
    protected $casts = [
        'locked' => 'boolean'
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update(['slug'=>$thread->title]);

        });


    }

    public function path()
    {
        //return '/threads/'.$this->id;
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);

    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param array $reply
     * @return Reply
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));


        return $reply;

    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)
    {


        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        {
            return 'slug';
        }
    }

    public function setSlugAttribute($value)
    {

        $slug = str_slug($value);

        if(static::whereSlug($slug)->exists()){
            $slug = "{$slug}-".$this->id;
        }

        $this->attributes['slug'] = $slug;
    }
    /**
     * Mark the given reply as the best answer.
     *
     * @param Reply $reply
     */
    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function toSearchableArray()
    {
        return $this->toArray() + ['path'=>$this->path()];
    }

    public function getBodyAttribute($body)
    {
      return \Purify::clean($body);
    }

}
