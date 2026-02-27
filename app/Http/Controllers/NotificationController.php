<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['isRead' => true]);

        return back();
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function markAllRead(Request $request)
    {
        $role = session('user_role');
        $userId = session('user_id');

        if ($role === 'buyer') {
            Notification::where('buyerID', $userId)->where('isRead', false)->update(['isRead' => true]);
        } elseif ($role === 'artist') {
            Notification::where('artistID', $userId)->where('isRead', false)->update(['isRead' => true]);
        } elseif ($role === 'admin') {
            Notification::where('adminID', $userId)->where('isRead', false)->update(['isRead' => true]);
        }

        return back();
    }
}
