<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Mark a single notification as read.
     * Only the owner (or a global notif visible to this user) may mark it.
     */
    public function markRead(Announcement $announcement)
    {
        // Authorize: must be a global broadcast or addressed to this user
        if ($announcement->user_id !== null && $announcement->user_id !== Auth::id()) {
            abort(403);
        }

        $announcement->markRead();

        return back();
    }

    /**
     * Mark ALL unread notifications for this user as read.
     */
    public function markAllRead()
    {
        Announcement::forUser(Auth::id())
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return back();
    }
}
