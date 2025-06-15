<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // ✅ API LOGIN (JWT)
    public function apiLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    // ✅ WEB LOGIN (SESSION)
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function webLogin(Request $request)
{
    $credentials = $request->only('email', 'password');
    
    // Debug 1: Lihat credentials yang diterima
    \Log::debug('Login Attempt:', $credentials);
    
    // Debug 2: Cek apakah user ada
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    \Log::debug('User Found:', $user ? $user->toArray() : ['message' => 'User not found']);
    
    // Debug 3: Verifikasi password manual
    if ($user) {
        \Log::debug('Password Match:', [
            'input' => $credentials['password'],
            'stored' => $user->password,
            'verify' => \Hash::check($credentials['password'], $user->password)
        ]);
    }
    
    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
        \Log::debug('Login Success:', Auth::user()->toArray());
        
        $role = Auth::user()->role;
        session()->flash('login_role', $role);
        
        return match ($role) {
            'HRGA'      => redirect()->route('hrga.dashboard'),
            'Karyawan'  => redirect()->route('karyawan.dashboard'),
            'Koki'      => redirect()->route('koki.dashboard'),
            default     => back()->withErrors(['email' => 'Role tidak dikenali.']),
        };
    }
    
    \Log::debug('Login Failed for:', [$credentials['email']]);
    return back()->withErrors([
        'email' => 'Login gagal. Periksa email dan password.',
    ]);
}


    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
