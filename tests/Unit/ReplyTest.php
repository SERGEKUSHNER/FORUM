<?php

namespace Tests\Unit;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase{
    use DatabaseTransactions;
/** @test */
function it_has_an_owner()
{
    //$reply = factory('App\Reply')->create();
    $reply = create('App\Reply');
    $this->assertInstanceOf('App\User', $reply->owner);
}
    /** @test */
    function it_knows_if_it_was_just_published(){
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());

    }
    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body(){

        $reply = new \App\Reply([
           'body'=>'@Dana wants to talk to @Serge'
        ]);

        $this->assertEquals(['Dana','Serge'],$reply->mentionedUsers());
    }
    /** @test */
    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @Dana.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Dana">@Dana</a>.',
            $reply->body
        );
    }
    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());

    }
    /** @test */
    function a_reply_body_is_sanitized_automatically()
    {
        $reply = make('App\Reply', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $reply->body);
    }
}

