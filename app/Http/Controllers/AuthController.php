<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the Maison client login view (UI Preview).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle incoming authentication attempt (UI Preview Mode).
     * Decoupled from database until Laravel Breeze is installed.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('info', 'UI Preview: Full authentication will be enabled once Laravel Breeze is installed.');
    }

    /**
     * Display the Maison registration view (UI Preview).
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle client registration request (UI Preview Mode).
     * Decoupled from database until Laravel Breeze is installed.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return back()
            ->withInput($request->only('name', 'email'))
            ->with('info', 'UI Preview: Account registration will be enabled once Laravel Breeze is installed.');
    }

    /**
     * Display the password reset request view (Forgot Password UI).
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset email request (UI Preview Mode).
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return back()
            ->withInput()
            ->with('status', 'UI Preview: Password reset emails will be sent once Laravel Breeze is configured.');
    }

    /**
     * Display the reset password form view with token (UI Preview).
     */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', 'customer@example.com'),
        ]);
    }

    /**
     * Handle password update request (UI Preview Mode).
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return redirect()->route('login')
            ->with('success', 'UI Preview: Password updated successfully! (Ready for Breeze)');
    }

    /**
     * Handle client logout (UI Preview Mode).
     */
    public function logout(): RedirectResponse
    {
        return redirect()->route('home');
    }
}
