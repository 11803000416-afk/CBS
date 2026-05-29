<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notifications = $user->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data ?? [];

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'System Notification',
                    'message' => $data['message'] ?? 'You have a new update.',
                    'url' => $data['url'] ?? route('dashboard'),
                    'category' => $data['category'] ?? 'general',
                    'unread' => $notification->read_at === null,
                    'time' => optional($notification->created_at)->diffForHumans(),
                ];
            })
            ->values();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notification = $user->notifications()->where('id', $id)->first();

        if (! $notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
