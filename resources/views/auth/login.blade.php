<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ 'Login Into Profile - ' . $bases['siteName'] ?? 'Login Into Profile' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <!-- project css file  -->
    <link rel="stylesheet" href="../assets/css/e-learn.style.min.css">
</head>

<body>

<div id="elearn-layout" class="theme-black">

    <!-- main body area -->
    <div class="main p-2 py-3 p-xl-5 ">

        <!-- Body: Body -->
        <div class="body d-flex p-0 p-xl-5">
            <div class="container-xxl">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(Session::has('success'))
                    @php
                        $success = Session::get('success');
                    @endphp

                    @if(is_array($success))
                        @foreach ($success as $message)
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-success">
                            {{ $success }}
                        </div>
                    @endif
                @endif

                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
                        <div style="max-width: 25rem;">
                            <div class="text-center mb-5">
                                <svg class="nano-logo" width="35" height="35" viewBox="0 0 64 64" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="5" />
                                    <circle cx="16" cy="16" r="4" />
                                    <circle cx="48" cy="16" r="4" />
                                    <circle cx="16" cy="48" r="4" />
                                    <circle cx="48" cy="48" r="4" />
                                    <line x1="32" y1="32" x2="16" y2="16" stroke="currentColor" stroke-width="2" />
                                    <line x1="32" y1="32" x2="48" y2="16" stroke="currentColor" stroke-width="2" />
                                    <line x1="32" y1="32" x2="16" y2="48" stroke="currentColor" stroke-width="2" />
                                    <line x1="32" y1="32" x2="48" y2="48" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </div>
                            <div class="mb-5">
                                <h2 class="color-900 text-center">{{ $bases['siteName'] }}</h2>
                            </div>
                            <!-- Image block -->
                            <div class="">
                                <a href="{{ env('URL_FRONT') }}">
                                <img src="https://admin.nanolympiad.org/logos/2025_03_04_111021_logo.png" alt="online-study">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
                        <div class="w-100 p-4 p-md-5 card border-0 bg-dark text-light" style="max-width: 32rem;">
                            <!-- Form -->
                            <form method="POST" action="{{ route('login.do') }}" class="row g-1 p-0 p-4">
                                @csrf

                                <div class="col-12 text-center mb-5">
                                    <h1>Sign in</h1>
                                    <span>Free access to our dashboard.</span>
                                </div>
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label class="form-label">Email address</label>
                                        <input name="email" type="email" class="form-control form-control-lg" placeholder="name@example.com">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-2">
                                        <div class="form-label">
                                            <span class="d-flex justify-content-between align-items-center">
                                                Password
                                                <a class="text-secondary" href="{{ env('URL_FRONT') }}/clientarea/forgot-password">Forgot Password?</a>
                                            </span>
                                        </div>
                                        <input name="password" type="password" class="form-control form-control-lg" placeholder="***************">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Captcha</label>
                                    <div class="d-flex align-items-center">
                                        <input name="captcha" type="text" class="form-control form-control-lg me-2" placeholder="Enter captcha">
                                        <img src="{{ url('/captcha-image') }}" alt="captcha" onclick="this.src='{{ url('/captcha-image') }}?'+Math.random()" style="cursor: pointer;">
                                    </div>
                                    @error('captcha')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault">

                                        <label class="form-check-label" for="flexCheckDefault">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-lg btn-block btn-light lift text-uppercase">SIGN IN</button>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <span class="text-muted">Don't have an account yet? <a href="{{ env('URL_FRONT') }}/register" class="text-secondary">Sign up here</a></span>
                                </div>
                            </form>
                            <!-- End Form -->
                            <div class="d-flex justify-content-between flex-wrap">
                                <div>
                                    <a href="https://www.youtube.com/@nanolympiad" class="me-2 text-muted"><i class="fa fa-youtube-square fa-lg"></i></a>
                                    <a href="https://api.whatsapp.com/send?phone=989351911793" class="me-2 text-muted"><i class="fa fa-whatsapp-square fa-lg"></i></a>
                                </div>
                                <div>
                                    <a href="https://ino-official.org/" title="home" class="me-2 text-muted">Home</a>
                                    <a href="https://ino-official.org/about" title="aboutus" class="me-2 text-muted">About Us</a>
                                    <a href="https://ino-official.org/contact" title="contactus" class="me-2 text-muted">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Row -->

            </div>
        </div>

    </div>

</div>

<!-- Jquery Core Js -->
<script src="../assets/bundles/libscripts.bundle.js"></script>
<script>
    (function() {
        const REDIRECT_URL = "{{ env('URL_FRONT') }}";
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                window.location.href = REDIRECT_URL;
            }, 60000); // 60,000 ms = 1 minute
        }

        // Activity events
        ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll'].forEach(event => {
            document.addEventListener(event, resetTimer, true);
        });

        resetTimer(); // start timer initially
    })();
</script>
</body>
</html>