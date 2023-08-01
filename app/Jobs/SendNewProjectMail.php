<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use App\Notifications\NewProjectNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNewProjectMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Project $project)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // send welcome email to new user
        $this->project->user->notify(new NewProjectNotification());

        info("New project email sent. Project title: {$this->project->title}");
    }
}
