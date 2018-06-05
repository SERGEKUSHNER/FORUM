<?php

namespace Tests\Unit;
use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    use DatabaseTransactions;
   /** @test */
    public function it_checks_for_invalid_keywords()
    {
      $spam = new Spam();

     $this->assertFalse($spam->detect('Innocent reply here'));

     $this->expectException('Exception');

     $spam->detect('yahoo customer support');
    }

    /** @test */
    function it_checks_for_any_key_being_held_down(){
        $spam = new Spam();
        $this->expectException('Exception');
        $spam->detect('Hello world aaaaa');

    }
}
