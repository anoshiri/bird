<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $resource = '/projects';


    public function test_that_project_page_requires_authentication(): void
    {
        $this->get($this->resource)->assertRedirect("login");
    }


    /**
     * Test the a user can create project.
     */
    public function test_a_user_can_create_a_project(): void
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $user = User::factory()->create();

        // create a resource
        $this->actingAs($user)->post($this->resource, $attributes)->assertRedirect($this->resource);

        // assert the resource exists
        $this->assertDatabaseHas('projects', $attributes);

        // get all resources
        $this->get($this->resource)->assertSee($attributes['title']);

        // get one resource
        $project = $this->get($this->resource.'/'. 1);
        $project->assertSee($attributes['title']);
        // delete the resource
    }


    public function test_a_project_can_be_updated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $response = $this
            ->actingAs($user)
            ->patch("{$this->resource}/{$project->id}", $attributes);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect("{$this->resource}/{$project->id}");

        $project->refresh();

        $this->assertSame($attributes['title'], $project->title);
        $this->assertSame($attributes['description'], $project->description);
    }

    public function test_a_user_can_delete_own_project()
    {
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $user = User::factory()->create();

        // create a resource
        $this->actingAs($user)->post($this->resource, $attributes)->assertRedirect($this->resource);

        // create another user and post
        $secondUser = User::factory()->create();
        $secondProject = Project::factory()->create(['user_id' => $secondUser->id]);

        // delete the second post with the first user access
        $response = $this->actingAs($user)
            ->delete("{$this->resource}/{$secondProject->id}");

        $response->assertStatus(403); // access denied

        // delete the user project
        $response = $this->actingAs($user)
            ->delete("{$this->resource}/1");

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/projects');
    }
}
