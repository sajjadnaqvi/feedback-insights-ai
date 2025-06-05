<?php

namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MakeAIService
{
    private $makeUrl;

    public function __construct()
    {
        $this->makeUrl = config('services.makeai.url');    
    }

    public function sendAnalyzeFeedbackRequest(Feedback $feedback)
    {
        $data = [
            'feedback_id' => $feedback->id,
            'feedback_comment' => $feedback->comment
        ];

        // $attempts = 0;
        // $maxAttempts = 3;
        // $timeoutSeconds = 1;
        // do {
        //     try {
                // $response = Http::timeout($timeoutSeconds)->post($this->makeUrl, $data);
                $response = Http::post($this->makeUrl, $data);
                Log::info("make response", $response->json());
                return $response->json();
            // } catch (\Illuminate\Http\Client\ConnectionException $e) {
        //         $attempts++;
        //         if ($attempts >= $maxAttempts) {
        //             throw $e;
        //         }

        //         usleep(500000); // 0.5 second
        //     }
        // } while ($attempts < $maxAttempts);
    }
}