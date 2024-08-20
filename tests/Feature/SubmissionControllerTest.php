<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSubmitEndpointReturnsAcceptedStatusForValidRequest()
    {
        $response = $this->postJson('/api/submit', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
        ]);

        $response->assertStatus(202);
    }

    public function testSubmitEndpointReturnsValidationErrorForMissingNameField()
    {
        $response = $this->postJson('/api/submit', [
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors'])
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.']
                ],
            ]);
    }
}
