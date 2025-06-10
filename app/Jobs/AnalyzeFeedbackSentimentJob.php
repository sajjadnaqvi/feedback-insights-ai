<?php

namespace App\Jobs;

use App\Models\Feedback;
use App\Services\MakeAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeFeedbackSentimentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $makeService;
    public $tries = 3;
    public $backoff = 2;

    /**
     * Create a new job instance.
     *
     * @param Feedback $feedback
     */
    public function __construct(public Feedback $feedback)
    {
        $this->makeService = new MakeAIService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->makeService->sendAnalyzeFeedbackRequest($this->feedback);
        } catch (\Exception $e) {
            Log::error("AnalyzeFeedbackSentimentJob failed: " . $e->getMessage(), [
                'feedback_id' => $this->feedback->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
