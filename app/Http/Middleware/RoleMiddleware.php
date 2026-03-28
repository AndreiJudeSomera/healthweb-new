<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {
  public function handle(Request $request, Closure $next, ...$roles) {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $userRole = (string) Auth::user()->role;

    // allow role:1,2 and role:2 etc. (multiple role access)
    $allowed = array_map('strval', $roles);

    if (!in_array($userRole, $allowed, true)) {
      abort(403, 'Unauthorized access');
    }

    return $next($request);
  }

  // (GARIC) : Below is legacy code ... keeping it in case something breaks :))
  // ---------------------------------------------------------------------------
  // public function handle(Request $request, Closure $next, $role) {
  //   if (!Auth::check()) {
  //     return redirect()->route('login');
  //   }

  //   // Assuming your users table has a `role` column (0,1,2)
  //   if (Auth::user()->role != $role) {
  //     abort(403, 'Unauthorized access');
  //   }

  //   return $next($request);
  // }
}
