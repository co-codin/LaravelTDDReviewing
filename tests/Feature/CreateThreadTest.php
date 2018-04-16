<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
      parent::setUp();
      $this->thread = create('App\Thread');
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
             ->assertRedirect('/login');

        $this->post('/threads')
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $response = $this->post('/threads', $this->thread->toArray());

        $this->get($response->headers->get('Location'))
             ->assertSee($this->thread->title)
             ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
      $this->publishThread(['title' => null])
           ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
      factory('App\Channel', 2)->create();

      $this->publishThread(['channel_id' => null])
          ->assertSessionHasErrors('channel_id');

      $this->publishThread(['channel_id' => 999])
          ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);


        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
