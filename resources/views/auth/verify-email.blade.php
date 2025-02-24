<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Vérification de l'E-mail - Habitat+</title>
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
        <section class="section min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="" class="logo d-flex align-items-center w-auto">
                                <img src="/assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">Habitat+</span>
                            </a>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Vérifiez votre E-mail</h5>
                                    <p class="text-center small">
                                        {{ __("Merci de vous être inscrit ! Veuillez vérifier votre e-mail en cliquant sur le lien que nous avons envoyé. Si vous ne l'avez pas reçu, demandez-en un autre.") }}
                                    </p>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <form method="POST" action="{{ route('verification.send') }}" onsubmit="showLoading()">
                                        @csrf
                                        <div>
                                            <button type="submit" class="btn btn-primary w-100">
                                                {{ __('Renvoyer l\'email de vérification') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
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
</main>


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

</body>

</html>
