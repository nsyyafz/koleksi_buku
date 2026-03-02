<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Koleksi Buku</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('purple/assets/images/favicon.png') }}" />
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo text-center">
                                <img src="{{ asset('purple/assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4 class="text-center">Halo! Selamat Datang</h4>
                            <h6 class="font-weight-light text-center">Silakan login untuk melanjutkan.</h6>
                            
                            <!-- FORM LOGIN BIASA -->
                            <form class="pt-3" method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <!-- Email -->
                                <div class="form-group">
                                    <input type="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="Email"
                                           required 
                                           autocomplete="email" 
                                           autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <!-- Password -->
                                <div class="form-group">
                                    <input type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Password"
                                           required 
                                           autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <!-- Remember Me -->
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Ingat Saya
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                                
                               <!-- Submit Button LOGIN BIASA -->
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn d-flex align-items-center justify-content-center" style="height: 48px; width: 100%">
                                        <i class="mdi mdi-login" style="font-size: 24px; margin-right: 10px;"></i>
                                        LOGIN
                                    </button>
                                </div>
                                
                                <!-- Forgot Password Link -->
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="auth-link text-primary">Lupa password?</a>
                                    @endif
                                </div>
                            </form>
                            <!-- END FORM LOGIN BIASA -->
                            
                            <!-- Divider OR -->
                            <div class="my-3 d-flex justify-content-between align-items-center">
                                <div style="flex: 1; height: 1px; background-color: #ddd;"></div>
                                <span style="padding: 0 15px; color: #999; font-weight: 500;">ATAU</span>
                                <div style="flex: 1; height: 1px; background-color: #ddd;"></div>
                            </div>

                            <!-- Google Login Button (DI LUAR FORM!) -->
                            <div class="mt-3">
                                <a href="{{ route('google.redirect') }}" 
                                   class="btn btn-block btn-lg font-weight-medium auth-form-btn d-flex align-items-center justify-content-center" 
                                   style="background-color: #4285f4; color: white; border: none;">
                                    <i class="mdi mdi-google" style="font-size: 24px; margin-right: 10px;"></i>
                                    Login dengan Google
                                </a>
                            </div>
                            
                            <!-- Register Link -->
                            <div class="text-center mt-4 font-weight-light">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('purple/assets/js/misc.js') }}"></script>
</body>
</html>