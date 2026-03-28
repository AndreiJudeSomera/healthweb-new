<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller {
  public function index(Request $request) {
    return view('audit.index');
  }

  public function data(Request $request) {
    $q = AuditLog::query()->latest()->with('user');

    if ($request->filled('action')) {
      $q->where('action', $request->action);
    }

    if ($request->filled('role')) {
      $q->whereHas('user', fn($u) => $u->where('role', $request->role));
    }

    if ($request->filled('date_from')) {
      $q->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
      $q->whereDate('created_at', '<=', $request->date_to);
    }

    return response()->json(
      $q->get()->map(fn($log) => [
        'id'          => $log->id,
        'created_at'  => $log->created_at->toIso8601String(),
        'user_name'   => $log->user?->username ?? $log->user?->name ?? 'Unknown',
        'user_role'   => $log->user?->role ?? -1,
        'action'      => $log->action,
        'description' => $log->actionDescription(),
      ])
    );
  }
}
