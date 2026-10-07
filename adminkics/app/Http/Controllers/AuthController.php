<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'staff.profile');
        }

        return redirect()->route('staff.login');
    }

    public function login(Request $request)
    {
        return redirect()->route('staff.login');
    }

    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            abort_unless(Auth::user()->role === 'admin', 403);
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login', [
            'loginType' => 'admin',
            'totalUsers' => \App\Models\User::count(),
        ]);
    }

    public function showStaffLoginForm()
    {
        if (Auth::check()) {
            abort_unless(Auth::user()->role === 'staff', 403);
            return redirect()->route('staff.profile');
        }

        return view('auth.login', [
            'loginType' => 'staff',
            'totalUsers' => \App\Models\User::count(),
        ]);
    }

    public function loginAdmin(Request $request)
    {
        return $this->authenticateForRole($request, 'admin');
    }

    public function loginStaff(Request $request)
    {
        return $this->authenticateForRole($request, 'staff');
    }

    private function authenticateForRole(Request $request, string $requiredRole)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->withInput();
        }

        if (Auth::user()->role !== $requiredRole) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'These credentials cannot be used on this sign-in page.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route($requiredRole === 'admin' ? 'admin.dashboard' : 'staff.profile'));
    }

    public function logout(Request $request)
    {
        $loginRoute = Auth::user()?->role === 'admin' ? 'admin.login' : 'staff.login';
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($loginRoute);
    }
}
