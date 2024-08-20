<?php

namespace App\Http\Controllers;

use App\Exceptions\JobProcessingException;
use App\Http\Requests\SubmissionRequest;
use App\Jobs\ProcessSubmission;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    public function submit(SubmissionRequest $request): JsonResponse
    {
            ProcessSubmission::dispatch($request->all());

            return response()->json(['message' => 'Submission received.'], 202);
    }
}
