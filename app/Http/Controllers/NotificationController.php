<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Notifications that belong to the logged-in user only.
     */
    private function mine()
    {
        $column = [
            'buyer' => 'buyerID',
            'artist' => 'artistID',
            'admin' => 'adminID',
        ][session('user_type')] ?? null;

        abort_unless($column && session('user_id'), 403);

        return Notification::where($column, session('user_id'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        // Scoped lookup: another user's notification ID is a 404, not a write.
        $this->mine()->where('notificationID', $id)->firstOrFail()->update(['isRead' => true]);

        return back();
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function markAllRead(Request $request)
    {
        $this->mine()->where('isRead', false)->update(['isRead' => true]);

        return back();
    }
}
