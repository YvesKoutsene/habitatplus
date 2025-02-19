<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Dashboard - Administrateur</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="\assets/img/favicon.png" rel="icon">
        <link href="\assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="\assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="\assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="\assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="\assets/vendor/quill/quill.snow.css" rel="stylesheet">
        <link href="\assets/vendor/quill/quill.bubble.css" rel="stylesheet">
        <link href="\assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="\assets/vendor/simple-datatables/style.css" rel="stylesheet">

        <link href="\assets/css/style.css" rel="stylesheet">
    </head>

    <body>

        <header id="header" class="header fixed-top d-flex align-items-center">
            @include('admin.include.partials.header')
        </header>

        <aside id="sidebar" class="sidebar">
            @include('admin.include.partials.sidebar')
        </aside>

        <main id="main" class="main">
            @include('admin.include.partials.message')
            @include('admin.include.partials.loading')
            @yield('content')
        </main>

        <footer id="footer" class="footer">
            @include('admin.include.partials.footer')
        </footer>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="\assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="\assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="\assets/vendor/chart.js/chart.umd.js"></script>
        <script src="\assets/vendor/echarts/echarts.min.js"></script>
        <script src="\assets/vendor/quill/quill.js"></script>
        <script src="\assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="\assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="\assets/vendor/php-email-form/validate.js"></script>
        <script src="\assets/js/main.js"></script>

        <style>
            html, body {
                height: 100%;
                margin: 0;
                display: flex;
                flex-direction: column;
            }

            main.main {
                flex: 1;
            }
        </style>
    </body>

</html>
