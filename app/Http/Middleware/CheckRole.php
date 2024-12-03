<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        Log::info('CheckRole middleware', [
            'user' => Auth::user(),
            'required_role' => $role,
            'current_role' => Auth::user()?->role,
        ]);

        if (!Auth::check()) {
            Log::warning('User not authenticated in CheckRole middleware');
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            Log::warning('User role mismatch', [
                'user_id' => $user->id_akun,
                'user_role' => $user->role,
                'required_role' => $role,
            ]);

            // Instead of aborting, redirect to the appropriate panel
            return $this->redirectToAppropriatePanel($user->role);
        }

        if ($user->role === null) {
            Log::error('User role is null', ['user_id' => $user->id_akun]);
            return redirect()->route('filament.admin.auth.login');
        }
        

        return $next($request);
    }

    protected function redirectToAppropriatePanel($role)
    {
        $route = match ($role) {
            'admin' => 'filament.admin.pages.dashboard',
            'dosen' => 'filament.dosen.pages.dashboard',
            'mahasiswa' => 'filament.mahasiswa.pages.dashboard',
            default => 'filament.admin.auth.login',
        };

        return redirect()->route($route);
    }
}
