<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $resource = '/projects';

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_create_a_project(): void
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        // create a resource
        $this->post($this->resource, $attributes)->assertRedirect($this->resource);

        // assert the resource exists
        $this->assertDatabaseHas('projects', $attributes);

        // get all resources
        $this->get($this->resource)->assertSee($attributes['title']);

        // get one resource
        $project = $this->get($this->resource.'/'. 1);
        $project->assertSee($attributes['title']);

        $secondAttributes = [
            'title' => $this->faker->sentence,
        ];

        // update project
        $project->title = $secondAttributes['title'];

        // update that resource
        $this->put($this->resource.'/'. 1, $secondAttributes)->assertRedirect($this->resource.'/'. 1);

        // get resource
        $project = $this->get($this->resource.'/'. 1);
        $project->assertSee($secondAttributes['title']);

        // delete the resource
    }
}
