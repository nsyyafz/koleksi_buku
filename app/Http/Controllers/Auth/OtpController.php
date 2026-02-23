<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    /**
     * Tampilkan form input OTP
     */
    public function showOtpForm()
    {
        // Cek apakah ada session otp_user_id
        if (!session('otp_user_id')) {
            return redirect()->route('login')
                           ->with('error', 'Session expired. Silakan login kembali.');
        }
        
        return view('auth.otp');
    }
    
    /**
     * Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        // Validasi input
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);
        
        // Ambil user ID dari session
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('login')
                           ->with('error', 'Session expired. Silakan login kembali.');
        }
        
        // Cari user
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'User tidak ditemukan.');
        }
        
        // Verifikasi OTP
        if ($user->otp === $request->otp) {
            // OTP benar!
            
            // Login user
            Auth::login($user);
            
            // Hapus OTP dari database (keamanan)
            $user->update(['otp' => null]);
            
            // Hapus session otp_user_id
            session()->forget('otp_user_id');
            
            // Redirect ke dashboard
            return redirect()->route('home')
                           ->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        } else {
            // OTP salah
            return back()
                   ->withErrors(['otp' => 'Kode OTP salah. Silakan coba lagi.'])
                   ->withInput();
        }
    }
    
    /**
     * Kirim ulang OTP
     */
    public function resendOtp()
    {
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('login')
                           ->with('error', 'Session expired. Silakan login kembali.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'User tidak ditemukan.');
        }
        
        // Generate OTP baru
        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);
        
        // Kirim ke email
        $details = [
            'title' => 'Kode OTP Login',
            'body' => 'Gunakan kode OTP berikut untuk melanjutkan login:',
            'otp' => $otp,
        ];
        
        \Mail::to($user->email)->send(new \App\Mail\OtpMail($details));
        
        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda!');
    }
}