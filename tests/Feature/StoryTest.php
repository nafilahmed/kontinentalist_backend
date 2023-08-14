<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Story;

class StoryTest extends TestCase
{
    protected $type = 'application/json';
    protected $userToken;
    protected $story;

    /**
     * Authenticate user.
     *
     */

    protected function authenticate()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'test',
            'email' => rand(12345,678910).'test@gmail.com',
            'password' => \Hash::make('secret9874'),
        ], ['Accepts' => $this->type]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);

        $this->assertArrayHasKey('access_token',$response->json());

        // Receive our token
        $this->userToken = $response->json()['access_token'];
    }

    protected function findStory()
    {
        $this->story = Story::latest('created_at')->first();        
    }

    /**
     * test create story.
     *
     * @return void
     */
    public function test_create_story()
    {
        $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->userToken,
            'Accepts' => $this->type
        ])->json('POST','api/story');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test get all stories.
     *
     * @return void
     */
    public function test_get_all_story()
    {        
        $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->userToken,
            'Accepts' => $this->type
        ])->json('GET','api/story');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test find story.
     *
     * @return void
     */
    public function test_find_story()
    {        
        $this->authenticate();

        $this->findStory();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->userToken,
            'Accepts' => $this->type
        ])->json('GET','api/story/'.$this->story->id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test update story.
     *
     * @return void
     */
    public function test_update_story()
    {
        $this->authenticate();

        $this->findStory();        

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->userToken,
            'Accepts' => $this->type
        ])->json('PUT','api/story/'.$this->story->id,[
            'title' => 'Star War Test story',
            'content' => 'Demo Content',
            'status' => "Draft",
            "view_draft" => 1
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test delete stories.
     *
     * @return void
     */
    public function test_delete_story()
    {        
        $this->authenticate();

        $this->findStory();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->userToken,
            'Accepts' => $this->type
        ])->json('DELETE','api/story/'.$this->story->id);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
