<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteUserController extends Controller
{
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password:web'],
        ], [
            'password.current_password' => __('The provided password does not match your current password.'),
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
