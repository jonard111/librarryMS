<?php
/*namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // Show login form
    public function show()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', strtolower($request->role))
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email and role.'])->withInput();
        }

        if ($user->account_status === 'pending') {
            return back()->withErrors(['account_status' => 'Your account is still pending approval.'])->withInput();
        }

        if ($user->account_status !== 'approved') {
            return back()->withErrors(['account_status' => 'Your account status is invalid.'])->withInput();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        // Set session (optional)
        session([
            'user_id' => $user->userId,
            'role' => $user->role,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);

        // Redirect based on role
        switch ($user->role) {
            case 'student':
                return redirect('/student/dashboard');
            case 'faculty':
                return redirect('/faculty/dashboard');
            case 'headlibrarian':
                return redirect('/head/dashboard');
            case 'assistant':
                return redirect('/assistant/dashboard');
            case 'admin':
                return redirect('/admin/dashboard');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
*/






namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // Show login form
    public function show()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,faculty,headlibrarian,assistant,admin',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])
                    ->where('role', $request->role)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email and role.'
            ])->withInput();
        }

        if ($user->account_status !== 'approved') {
            return back()->withErrors([
                'email' => 'Your account is not yet approved by admin.'
            ])->withInput();
        }

        // Attempt authentication with email and password
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'student':
                    return redirect()->route('student.dashboard');
                case 'faculty':
                    return redirect()->route('faculty.dashboard');
                case 'assistant':
                    return redirect()->route('assistant.dashboard');
                case 'headlibrarian':
                    return redirect()->route('head.dashboard');
                default:
                    return redirect()->route('login')->withErrors([
                        'email' => 'Invalid role.'
                    ]);
            }
        }

        return back()->withErrors([
            'password' => 'Incorrect password.'
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
