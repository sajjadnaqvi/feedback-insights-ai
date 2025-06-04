<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeFeedbackSentimentJob;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function showGiveFeedback()
    {
        return view('feedback.give');
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        AnalyzeFeedbackSentimentJob::dispatch($feedback);
        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function index(Request $request)
    {
        $feedbacks = Feedback::get();
        return view('feedback.index', compact('feedbacks'));
    }
}
