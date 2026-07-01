<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $user = Auth::user();
        $redirect = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'officer' => route('dashboard'),
            default => route('dashboard'),
        };

        return redirect()->intended($redirect);
    }
}
