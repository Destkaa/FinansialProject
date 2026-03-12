<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register | Destkaa</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <style>
        /* 1. Background Animasi Gradasi (Sama dengan Login) */
        body {
            background: linear-gradient(-45deg, #696cff, #8e91ff, #f5f5f9, #e7e7ff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* 2. Glassmorphism Card */
        .authentication-inner {
            max-width: 450px;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 1.2rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            animation: fadeInScale 0.6s ease-out;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* 3. Input & Button Focus */
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.15);
        }

        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(105, 108, 255, 0.4);
        }

        /* 4. Brand & Typography */
        .app-brand-text {
            font-size: 1.8rem !important;
            letter-spacing: -1px;
            background: linear-gradient(to right, #696cff, #30328a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" fill="#696cff" />
                                    </svg>
                                </span>
                                <span class="app-brand-text demo fw-bolder text-capitalize">Destkaa</span>
                            </a>
                        </div>

                        <div class="text-center">
                            <h4 class="mb-2">Mulai Perjalanan Anda 🚀</h4>
                            <p class="mb-4 text-muted">Daftar sekarang untuk mengelola keuangan dengan cerdas.</p>
                        </div>

                        <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus />
                                </div>
                                @error('name')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required />
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="············" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password-confirm">Konfirmasi Sandi</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-check-shield"></i></span>
                                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="············" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required />
                                    <label class="form-check-label" for="terms-conditions">
                                        Saya setuju dengan <a href="javascript:void(0);">Kebijakan Privasi</a>
                                    </label>
                                </div>
                            </div>
                            
                            <button class="btn btn-primary d-grid w-100" type="submit">Daftar Sekarang</button>
                        </form>

                        <p class="text-center">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}" class="fw-bold text-primary">
                                <span>Masuk Disini</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
  $(document).ready(function() {
    $('.form-password-toggle .input-group-text.cursor-pointer').on('click', function(e) {
      e.preventDefault();
      let $this = $(this);
      let $input = $this.closest('.input-group').find('input');
      let $icon = $this.find('i');

      // Tambahkan animasi CSS via JS
      $icon.css({
        'transition': 'all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
        'transform': 'scale(1.3) rotate(15deg)'
      });

      setTimeout(() => {
        if ($input.attr('type') === 'text') {
          $input.attr('type', 'password');
          $icon.removeClass('bx-show').addClass('bx-hide');
        } else {
          $input.attr('type', 'text');
          $icon.removeClass('bx-hide').addClass('bx-show');
        }
        
        // Kembalikan ke ukuran normal
        $icon.css('transform', 'scale(1) rotate(0deg)');
      }, 150); // Delay dikit biar efek "pop" kerasa
    });

    // Tambahkan efek hover biar user tau itu bisa diklik
    $('.form-password-toggle .input-group-text.cursor-pointer').hover(
      function() { $(this).find('i').css('color', '#696cff'); },
      function() { $(this).find('i').css('color', ''); }
    );
  });
  </script>
</body>
</html>