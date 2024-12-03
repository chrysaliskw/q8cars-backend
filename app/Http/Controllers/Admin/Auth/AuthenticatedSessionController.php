<?php

namespace App\Http\Controllers\Admin\Auth;

use Exception;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\ResetPasswordLink;
use App\Http\Requests\Admin\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function forgotPassword(Request $request)
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|string|email:filter|max:255',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (empty($admin)) {
            return redirect()->back()->with('error', 'Provided Email is not registered');
        }

        Mail::to($admin->email)->send(new ResetPasswordLink($admin));

        return redirect()->back()->with('success', 'Password reset link is send to your registered Email ID');
    }

    public function showResetPasswordForm(Request $request)
    {
        $admin = Admin::first();

        return view('admin.auth.reset-password', compact('admin'));
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => [
                'required',
                'string',
                'max:200',
                Rule::exists(Admin::class)
            ],

            'password' => 'required|string|min:8|max:15|regex:/^[a-zA-Z\d!@#$%^&*_]*$/',
            'confirm_password' => 'required|same:password',

        ]);

        try {
            $admin = Admin::where('email', $request->email)->first();
            $admin->password = Hash::make($validatedData['password']);
            $admin->update();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'));
        }
        return redirect()->route('admin.login')->with('success', 'Password updated successfully!');
    }
}
