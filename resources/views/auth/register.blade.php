<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Pages / Inscription - Habitat+ Admin Dashboard</title>
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

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Apr 20 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>
<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <a href="#" class="logo d-flex align-items-center w-auto">
                                <img src="\assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">Habitat+</span>
                            </a>
                        </div><!-- End Logo -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Créer un compte</h5>
                                    <p class="text-center small">Entrez vos informations personnelles</p>
                                </div>
                                <form method="POST" action="{{ route('register') }}" class="row g-3 needs-validation" novalidate>
                                    @csrf

                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Nom<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="yourName" required>
                                        <div class="invalid-feedback">S'il vous plaît, entrez votre nom!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Email<span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" id="yourEmail" required>
                                        <div class="invalid-feedback">Veuillez entrer une adresse e-mail valide!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="inputState" class="form-label">Téléphone<span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select id="inputState" class="form-select" onchange="updatePhoneLength()" name="pays">
                                                    <option value="">Choisir un indicatif...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="numero" class="form-control" id="numero" required oninput="validateInput()">
                                                <div class="invalid-feedback">Veuillez entrer votre numéro!</div>
                                                <div id="phoneLengthWarning" class="text-danger" style="display:none;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Mot de passe<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="password" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                                <i class="bi bi-eye" id="eye-icon-password"></i>
                                            </button>
                                            <div class="invalid-feedback">Veuillez entrer votre mot de passe!</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password_confirmation" class="form-label">Confirmez mot de passe<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                                <i class="bi bi-eye" id="eye-icon-confirmation"></i>
                                            </button>
                                            <div class="invalid-feedback">Veuillez confirmer votre mot de passe!</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">J'accepte les <a href="#">termes et conditions</a></label>
                                            <div class="invalid-feedback">Vous devez accepter avant de soumettre</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
<!--
                        <div class="credits">
                            Conçu par<a href="https://github.com/YvesKoutsene" target="_blank"> Jean-Yves</a>
                        </div>
-->
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const selectElement = document.getElementById("inputState");

                // Trier les pays par ordre alphabétique
                data.sort((a, b) => {
                    const nameA = a.translations.fra?.common || a.name.common; // Nom en français ou anglais
                    const nameB = b.translations.fra?.common || b.name.common; // Nom en français ou anglais
                    return nameA.localeCompare(nameB);
                });

                data.forEach(country => {
                    const indicatif = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');
                    const nameInFrench = country.translations.fra?.common || country.name.common; // Nom en français ou anglais
                    const option = document.createElement("option");
                    option.value = indicatif;
                    option.textContent = `${indicatif} - ${nameInFrench}`;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des données:', error));
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId === 'password' ? 'eye-icon-password' : 'eye-icon-confirmation');

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

    function validateInput() {
        const input = document.getElementById('numero');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

</script>

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

</body>

</html>
