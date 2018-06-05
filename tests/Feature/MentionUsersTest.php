<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{
//    use DatabaseMigrations;
use DatabaseTransactions;
        /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
      $serge = create('App\User',['name'=>'Serge']);
      $this->signIn($serge);
      $dana = create('App\User',['name'=>'Dana']);
      $thread = create('App\Thread');
      $reply = make('App\Reply', [
          'body'=>'Hey @Dana check this out.'
      ]);
        $this->json('post', $thread->path() . '/replies', $reply->toArray());
        $this->assertCount(1, $dana->notifications);
    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);
        $results = $this->json('GET', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $results->json());
    }

}
