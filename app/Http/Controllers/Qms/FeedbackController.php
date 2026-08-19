<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsFeedbackItem;
use App\Support\QmsNumbering;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = QmsFeedbackItem::query()->latest();

        if (($user->qms_role ?? null) === 'Reporter') {
            $query->where('user_id', $user->id);
        }

        return view('qms.feedback.index', [
            'feedbackItems' => $query->paginate(12)->withQueryString(),
            'layout' => ($user->qms_role ?? null) === 'Reporter' ? 'reporter.layout' : 'qms.layout',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'context' => ['nullable', 'string', 'max:160'],
            'feedback_type' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        QmsFeedbackItem::create([
            'reference' => QmsNumbering::next('NUM-FBK', 'Feedback', 'FBK'),
            'user_id' => $request->user()->id,
            'context' => $data['context'] ?? null,
            'feedback_type' => $data['feedback_type'],
            'message' => $data['message'],
            'status' => 'New',
            'visibility' => 'Support',
            'metadata' => [
                'source_url' => url()->previous(),
                'separate_from_safety_reporting' => true,
            ],
        ]);

        return back()->with('status', 'Feedback sent to the QMS support queue.');
    }
}
