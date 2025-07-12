<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AESCBC;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $payload = json_encode([
            'id' => $user->id,
            'email' => $user->email,
            'login_at' => now()->toDateTimeString(),
        ]);

        $token = AESCBC::encrypt($payload, config('app.aes_key'), config('app.aes_iv'));

        session()->put('auth_token', $token);

        // Simpan token ke file
  
        Storage::disk('local')->put("tokens/{$user->id}.token", $token);
       
        return $user->role === 'admin'
            ? redirect()->route('products.index')
            : redirect()->route('customers.index');
    }

    return back()->with('error', 'Email atau password salah');
}


    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();    // Hapus semua session
        request()->session()->regenerateToken(); // Untuk keamanan CSRF

        return redirect('/login');
    }

}
