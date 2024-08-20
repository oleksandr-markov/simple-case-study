<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Jobs\ProcessSubmission;
use Exception;
use Illuminate\Http\JsonResponse;

class SubmissionController extends Controller
{
    public function submit(SubmissionRequest $request): JsonResponse
    {
        try {
            ProcessSubmission::dispatch($request->all());

            return response()->json(['message' => 'Submission received.'], 202);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to process submission.',
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ], 500);
        }
    }
}
