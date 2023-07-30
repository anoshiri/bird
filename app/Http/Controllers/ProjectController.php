<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

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
        Project::create(request(['title', 'description']));

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
        $project->title = $request->input('title');
        $project->save();

        // return resource
        return redirect('/projects/'.$project->id);
    }
}
