<?php

namespace App\Http\Controllers;

class NotificationController extends Controller {
  public function index() {
    $user = auth()->user();

    return response()->json([
      'unread_count'  => $user->unreadNotifications()->count(),
      'notifications' => $user->notifications()->latest()->take(20)->get()->map(fn($n) => [
        'id'      => $n->id,
        'message' => $n->data['message'],
        'link'    => $n->data['link'] ?? null,
        'read'    => !is_null($n->read_at),
        'time'    => $n->created_at->diffForHumans(),
      ]),
    ]);
  }

  public function markRead(string $id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return response()->json(['ok' => true]);
  }

  public function markAllRead() {
    auth()->user()->unreadNotifications->markAsRead();

    return response()->json(['ok' => true]);
  }
}