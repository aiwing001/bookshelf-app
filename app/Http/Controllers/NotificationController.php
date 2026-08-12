<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function read($notification)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notification);

        $notification->markAsRead();

        return back();
    }
}
