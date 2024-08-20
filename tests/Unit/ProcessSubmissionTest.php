<?php

namespace Tests\Unit;

use App\Events\SubmissionSaved;
use App\Jobs\ProcessSubmission;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use PDOException;
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

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItLogsErrorWhenDatabaseExceptionOccurs()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ];

        Log::spy();
        $submissionMock = Mockery::mock('overload:' . Submission::class);
        $submissionMock->shouldReceive('create')
            ->andThrow(new PDOException('Database error'));

        $job = new ProcessSubmission($data);
        $job->handle();

        $logged = Log::shouldHaveReceived('error')->once()->with(
            'Failed with database process submission.',
            Mockery::on(function ($context) use ($data) {
                return $context['error'] === 'Database error' &&
                    $context['data'] === $data;
            })
        );

        $this->assertTrue($logged != null, 'Log::error was not called as expected.');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
