<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitat+, la plateforme d'immobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favicons -->
    <link href="\assets/img/favicon.png" rel="icon">
    <link href="\assets/img/apple-touch-icon.png" rel="apple-touch-icon">

</head>
<body class="d-flex flex-column min-vh-100">
@include('abonné.include.partials.header')
@include('abonné.pages.auth.auth')

<div class="container my-4 flex-grow-1">
    <div class="row habitat">
        <aside class="col-12 col-md-3 d-none d-md-block">
            <div class="pagetitle text-center mb-2">
                <p class="text-muted text-start">Mon compte</p>
            </div>
            @include('abonné.include.partials.sidebars')
        </aside>

        <aside class="col-12 col-md-3 d-md-none">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle px-2 py-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Mon compte</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @include('abonné.include.partials.sidebars')
                </ul>
            </div>
        </aside>

        <main class="col-12 col-md-9">
            <div class="content">
                @include('abonné.include.partials.loading')
                @include('abonné.include.partials.message')
                @yield('content')
                @include('abonné.pages.auth.edit')
            </div>
        </main>
    </div>
</div>

@include('abonné.include.partials.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<style>

    .habitat{
        margin-top: 80px;
    }

    @media (max-width: 768px) {
        .habitat{
            margin-top: 50px;
        }
    }

</style>
</body>
</html>
