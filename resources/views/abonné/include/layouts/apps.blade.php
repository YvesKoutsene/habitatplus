<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitat+, la plateforme d'immobilier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href=
          "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href=
          "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          rel="stylesheet">

    <script src=
            "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
    </script>

</head>
<body class="d-flex flex-column min-vh-100">
@include('abonné.include.partials.header')
@include('abonné.pages.auth.auth')

<div class="container my-4 flex-grow-1">
    <div class="row">
        <aside class="col-md-3">
            @include('abonné.include.partials.sidebars')
        </aside>
        <main class="col-md-9">
            <div class="content">
                @include('admin.include.partials.message')
                @yield('content')
            </div>
        </main>
    </div>
</div>

@include('abonné.include.partials.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
