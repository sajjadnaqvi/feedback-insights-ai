<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            Log::info("listening webhook");
            $token = $request->header('X-Webhook-Token') ?? null;

            if (!$token && $token !== config('services.webhook.secret')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $data = $request->get('data');
            $sentimentData = $data['sentiments'][0];
            $feedbackId = $data['feedback_id'];
            
            usort($sentimentData, function ($a, $b) {
                return $b['score'] <=> $a['score'];    
            });
            
            Log::info("sentiment data", ['data' => $sentimentData]);

            Feedback::FindOrFail($feedbackId)->update(['sentiment' => $sentimentData[0]['label']]);
        } catch (\Exception $e) {
            Log::error("webhook error", ['error' => $e->getMessage()]);
        }
    }
}
