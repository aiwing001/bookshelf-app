<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function read(string $notification): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notification);

        $notification->markAsRead();

        return redirect()->route('notifications.index');
    }
}
