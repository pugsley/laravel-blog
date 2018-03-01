<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    /**
     * @param array $attr
     * @return User;
     */
    public function createUser(array $attr = [])
    {
        return User::create(array_merge([
            'name'     => 'Jane Doe',
            'email'    => 'jane@example.org',
            'password' => 'password123',
        ], $attr));
    }

    /**
     * @param User $user
     * @param array $attr
     * @return BlogPost
     */
    public function createPost(User $user, array $attr = [])
    {
        return BlogPost::create(array_merge([
            'user_id' => $user->getId(),
            'title'   => "A fascinating title",
            'blurb'   => "An interesting blurb",
            'content' => "A fairly boring load of content",
        ], $attr));
    }

    /**
     * Make a request to the blog api listing page and ensure it's returning
     * the created blog post and user
     * @return void
     */
    public function testIndex()
    {
        $email = "moose@exmaple.org";
        $user = $this->createUser(['email' => $email]);
        $title = "A quick brown fox jumps over the lazy dog";
        $post = $this->createPost($user, [
            'title' => $title,
        ]);

        $uri = route('blog.index', [], false);
        $response = $this->get($uri);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        $this->assertEquals($email, $data[0]['user']['email']);
        $this->assertEquals($title, $data[0]['title']);
    }

    /**
     * Test the store route without being logged in
     */
    public function testStoreWithoutAuth()
    {
        $uri = route('blog.store', [], false);
        $response = $this->postJson($uri, []);
        $response->assertStatus(401);
    }

    /**
     * Testing storing a new post
     */
    public function testStoreWithAuth()
    {
        $user = $this->createUser();
        Auth::loginUsingId($user->getId());

        $data = [
            'user_id' => 999, // This should be ignored
            'title'   => "Monkey magic",
            'blurb'   => "Goose McBoose",
            'content' => "I've been reading too many kids books",
        ];

        $uri = route('blog.store', $data, false);
        $response = $this->postJson($uri, []);

        $response->assertStatus(201);
        $returned = $response->json();

        // First ensure the newly created post data is correct and is being returned with a user
        $this->assertArrayHasKey("user", $returned);
        $this->assertEquals($user->getId(), $returned['user_id']);
        $this->assertEquals($user->getId(), $returned['user']['id']);

        // Then sanity check the data
        $this->assertEquals($data['title'], $returned['title']);
        $this->assertEquals($data['blurb'], $returned['blurb']);
        $this->assertEquals($data['content'], $returned['content']);

        // Then ensure the object was successfully created in the db
        $post = BlogPost::find($returned['id']);
        $this->assertEquals($user->getId(), $post->user_id);
        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($data['blurb'], $post->blurb);
        $this->assertEquals($data['content'], $post->content);
    }

    // TODO: Test the other routes

}
