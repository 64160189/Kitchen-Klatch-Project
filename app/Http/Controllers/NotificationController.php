<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);

            // Check if the notification belongs to the logged-in user
            if ($notification->user_id !== auth()->id()) {
                return redirect('/')->with('error', "You don't have permission to do that.");
            }

            if (!$notification->is_read) {
                $notification->is_read = true;
                $notification->save();
            }

            if ($notification->notifiable_type === 'comment') {
                // Redirect to the post page for comment notifications
                return redirect()->route('post.show', ['id' => $notification->post_id]);
            } elseif ($notification->notifiable_type === 'delete') {
                // For delete notifications, display the modal content
                return view('notifications.delete-modal', [
                    'postTitle' => $notification->post_title,
                    'message' => $notification->message,
                    'postImage' => $notification->post_image,
                ]);
            }
        } catch (ModelNotFoundException $e) {
            // If the notification does not exist, redirect to the homepage with an error message
            return redirect('/')->with('error', "You don't have permission to do that.");
        }
    }

    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    use Notifiable; // Ensure you import this correctly at the top

    public function showNotifications()
    {
        // Get the latest 10 unread notifications
        $notifications = auth()->user()->unreadNotifications // Laravel's built-in notifications relationship
            ->take(10) // Limit to 10
            ->get();

        return view('partials.notifications', compact('notifications'));
    }
}
