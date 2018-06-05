<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Reply;
use App\Notifications\YouWereMentioned;

class NotifySubscribers
{
      /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {


        $event->reply->thread->subscriptions
            ->where('user_id','!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);

   }
}
