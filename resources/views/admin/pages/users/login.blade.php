<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Pages / Connexion - Habitat+ Admin Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="\assets/img/favicon.png" rel="icon">
    <link href="\assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="\assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="\assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="\assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="\assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="\assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="\assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="\assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="\assets/css/style.css" rel="stylesheet">

</head>

<body>

<main>
    @include('admin.include.partials.message')
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="" class="logo d-flex align-items-center w-auto"> <!--$roles-->
                                <img src="\assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">Habitat+</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Connexion à mon compte</h5>
                                    <p class="text-center small">Entrez votre email et votre mot de passe</p>
                                </div>

                                <form method="POST" action="{{ route('auth-admin') }}" class="row g-3 needs-validation" novalidate>
                                    @csrf

                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Email<span class="text-danger" title="obligatoire">*</span></label>
                                        <input type="email" name="email" class="form-control" id="yourEmail" value="{{ old('email') }}" required autofocus>
                                        <div class="invalid-feedback">Veuillez entrer votre adresse e-mail valide!</div>
                                        <!--
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        -->
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="yourPassword" required autocomplete="current-password">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('yourPassword')">
                                                <i class="bi bi-eye" id="eye-icon-password"></i>
                                            </button>
                                            <div class="invalid-feedback">Veuillez entrer votre mot de passe!</div>
                                            <!--
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            -->
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="true">
                                            <label class="form-check-label" for="rememberMe">Se rappeler de moi</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Se connecter</button>
                                    </div>
                                    <!--
                                                                        <div class="col-12">
                                                                            <p class="small mb-0">Je n'ai pas de compte? <a href="{{ route('register') }}">S'inscrire</a></p>
                                                                        </div>

                                                                        @if (Route::has('password.request'))
                                                                            <div class="col-12">
                                                                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                                                                    {{ __('Mot de passe oublié?') }}
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                    -->
                                </form>
                            </div>
                        </div>

                        <div class="credits">
                            Conçu par<a href="https://github.com/YvesKoutsene" target="_blank"> Jean-Yves</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="\assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="\assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="\assets/vendor/chart.js/chart.umd.js"></script>
<script src="\assets/vendor/echarts/echarts.min.js"></script>
<script src="\assets/vendor/quill/quill.js"></script>
<script src="\assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="\assets/vendor/tinymce/tinymce.min.js"></script>
<script src="\assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="\assets/js/main.js"></script>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId === 'yourPassword' ? 'eye-icon-password' : 'eye-icon-confirmation');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

</body>

</html>
