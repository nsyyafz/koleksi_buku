<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP - Koleksi Buku</title>
    
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('purple/assets/images/favicon.png') }}" />
    
    <style>
        .otp-input-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .otp-input:focus {
            border-color: #b66dff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(182, 109, 255, 0.1);
        }
        .resend-link {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-5 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo text-center">
                                <img src="{{ asset('purple/assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4 class="text-center mt-3">Verifikasi OTP</h4>
                            <h6 class="font-weight-light text-center mb-4">
                                Kode OTP telah dikirim ke email Anda
                            </h6>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if($errors->has('otp'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle"></i> {{ $errors->first('otp') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <form class="pt-3" method="POST" action="{{ route('otp.verify') }}" id="otp-form">
                                @csrf
                                
                                <!-- OTP Input (6 boxes) -->
                                <div class="otp-input-container">
                                    <input type="text" class="otp-input" maxlength="1" id="otp1" autofocus>
                                    <input type="text" class="otp-input" maxlength="1" id="otp2">
                                    <input type="text" class="otp-input" maxlength="1" id="otp3">
                                    <input type="text" class="otp-input" maxlength="1" id="otp4">
                                    <input type="text" class="otp-input" maxlength="1" id="otp5">
                                    <input type="text" class="otp-input" maxlength="1" id="otp6">
                                </div>
                                
                                <!-- Hidden input untuk submit -->
                                <input type="hidden" name="otp" id="otp-full">
                                
                                <!-- Submit Button -->
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn d-flex align-items-center justify-content-center" style="height: 48px; width: 100%">
                                        <i class="mdi mdi-check"></i> VERIFIKASI
                                    </button>
                                </div>
                                
                                <!-- Resend Link -->
                                <div class="text-center mt-4 font-weight-light">
                                    Tidak menerima kode? 
                                    <a href="{{ route('otp.resend') }}" class="text-primary resend-link">
                                        <i class="mdi mdi-reload"></i> Kirim Ulang
                                    </a>
                                </div>
                                
                                <!-- Back to Login -->
                                <div class="text-center mt-2 font-weight-light">
                                    <a href="{{ route('login') }}" class="text-muted">
                                        <i class="mdi mdi-arrow-left"></i> Kembali ke Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('purple/assets/js/misc.js') }}"></script>
    
    <script>
        // Auto focus & auto move ke input berikutnya
        const inputs = document.querySelectorAll('.otp-input');
        
        inputs.forEach((input, index) => {
            // Input event - pindah ke next input
            input.addEventListener('input', (e) => {
                // Hanya terima angka
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            
            // Keydown event - handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            
            // Paste event - auto fill semua input
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                for (let i = 0; i < Math.min(pasteData.length, 6); i++) {
                    inputs[i].value = pasteData[i];
                }
                
                // Focus ke input terakhir yang terisi
                const lastFilledIndex = Math.min(pasteData.length - 1, 5);
                inputs[lastFilledIndex].focus();
            });
        });
        
        // Submit form - gabungkan semua input jadi 1 OTP
        document.getElementById('otp-form').addEventListener('submit', (e) => {
            const otp = Array.from(inputs).map(input => input.value).join('');
            document.getElementById('otp-full').value = otp;
            
            // Validasi: harus 6 digit
            if (otp.length !== 6) {
                e.preventDefault();
                alert('Mohon masukkan 6 digit kode OTP');
                inputs[0].focus();
            }
        });
        
        // Auto submit saat input ke-6 diisi
        inputs[5].addEventListener('input', (e) => {
            if (e.target.value.length === 1) {
                // Delay 300ms supaya user sempat lihat input terakhir
                setTimeout(() => {
                    document.getElementById('otp-form').submit();
                }, 300);
            }
        });
    </script>
</body>
</html>