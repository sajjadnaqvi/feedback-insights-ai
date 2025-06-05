<?php

namespace App\Jobs;

use App\Models\Feedback;
use App\Services\MakeAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyzeFeedbackSentimentJob implements ShouldQueue
{
    use Queueable;

    private $makeService;
    public $tries = 3;
    public $backoff = 2;

    /**
     * Create a new job instance.
     */
    public function __construct( public Feedback $feedback)
    {
        $this->makeService = new MakeAIService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->makeService->sendAnalyzeFeedbackRequest($this->feedback);
        } catch (\Exception $e) {
            Log::error("AnalyzeFeedbackSentimentJob failed: " . $e->getMessage());
        }
        
        
    }
}
