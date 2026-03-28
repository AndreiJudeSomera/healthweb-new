<?php
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogService {
  public function log(
    string $action,
    ?int $userId = null,
    ?string $entityType = null,
    $entityId = null,
    array $meta = [],
    array $changes = [],
    ?Request $request = null
  ): AuditLog {
    $request ??= request();

    return AuditLog::create([
      'user_id' => $userId,
      'action' => $action,
      'entity_type' => $entityType,
      'entity_id' => $entityId,
      'route' => $request?->path(),
      'ip' => $request?->ip(),
      'user_agent' => substr((string) $request?->userAgent(), 0, 255),
      'meta' => $meta ?: null,
      'changes' => $changes ?: null,
    ]);
  }

  // optional helper: build {field:{from,to}} from old+new arrays
  public function diff(array $before, array $after, array $onlyKeys = []): array {
    if ($onlyKeys) {
      $before = array_intersect_key($before, array_flip($onlyKeys));
      $after = array_intersect_key($after, array_flip($onlyKeys));
    }

    $out = [];
    foreach ($after as $k => $v) {
      $old = $before[$k] ?? null;
      if ($old !== $v) {
        $out[$k] = ['from' => $old, 'to' => $v];
      }

    }
    return $out;
  }
}
