<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Jobs\SendNewProjectMail;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects', compact('projects'));
    }

    public function store()
    {
        // validate

        // save
        $project = auth()->user()->projects()->create(request(['title', 'description']));

        // dispatch a job
        SendNewProjectMail::dispatch($project);

        // redirect
        return redirect('/projects');
    }

    public function show(Project $project): Project
    {
        return $project;
    }

    public function update(Project $project, Request $request)
    {
        // validate input

        // save to db
        $project->update(request(['title', 'description']));

        // return resource
        return redirect('/projects/'.$project->id);
    }

    public function destroy(Project $project)
    {
        if ($project->user_id <> auth()->user()->id) {
            abort(403);
        }

        $project->delete();

        return redirect('/projects');
    }
}
