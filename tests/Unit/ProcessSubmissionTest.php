<?php

namespace Tests\Unit;

use App\Events\SubmissionSaved;
use App\Jobs\ProcessSubmission;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function testJobProcessesSubmissionAndSavesToDatabase()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ];

        $job = new ProcessSubmission($data);

        $job->handle();

        $this->assertDatabaseHas('submissions', $data);
    }

    public function testEventHasCorrectSubmissionData()
    {
        $submission = new Submission([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ]);

        $event = new SubmissionSaved($submission);

        $this->assertEquals('John Doe', $event->submission->name);
        $this->assertEquals('john.doe@example.com', $event->submission->email);
        $this->assertEquals('This is a test message.', $event->submission->message);
    }

}
