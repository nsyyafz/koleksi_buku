<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah user login
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada di database
            $user = User::where('email', $googleUser->email)
                       ->orWhere('id_google', $googleUser->id)
                       ->first();
            
            if ($user) {
                // User sudah ada, update id_google jika belum ada
                if (!$user->id_google) {
                    $user->update(['id_google' => $googleUser->id]);
                }
            } else {
                // User baru, buat akun baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'id_google' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)), // Password random
                ]);
            }
            
            // Generate OTP 6 digit
            $otp = rand(100000, 999999);
            
            // Simpan OTP ke database
            $user->update(['otp' => $otp]);
            
            // Kirim OTP ke email
            $this->sendOtpEmail($user, $otp);
            
            // Simpan user ID di session
            session(['otp_user_id' => $user->id]);
            
            // Redirect ke halaman OTP
            return redirect()->route('otp.form')
                           ->with('success', 'Kode OTP telah dikirim ke email ' . $user->email);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->with('error', 'Login dengan Google gagal: ' . $e->getMessage());
        }
    }
    
    /**
     * Kirim OTP ke email
     */
    private function sendOtpEmail($user, $otp)
    {
        $details = [
            'title' => 'Kode OTP Login',
            'body' => 'Gunakan kode OTP berikut untuk melanjutkan login:',
            'otp' => $otp,
        ];
        
        \Mail::to($user->email)->send(new \App\Mail\OtpMail($details));
    }
}