<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsNotification::query()->latest();

        if ($request->query('status') === 'unread') {
            $query->whereNull('read_at');
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhere('source_reference', 'like', "%{$search}%");
            });
        }

        return view('qms.notifications.index', [
            'notifications' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function markRead(QmsNotification $notification)
    {
        $notification->update(['read_at' => now()]);

        return back()->with('status', 'Notification marked as read.');
    }
}
