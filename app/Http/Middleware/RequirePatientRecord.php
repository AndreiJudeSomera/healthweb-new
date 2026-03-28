<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirePatientRecord
{
    public function handle(Request $request, Closure $next)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient || !$patient->record_id) {
            return redirect()->route('patient.onboarding.usertype');
        }

        return $next($request);
    }
}
