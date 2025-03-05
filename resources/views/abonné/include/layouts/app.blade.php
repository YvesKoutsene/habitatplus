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
        <div class="pagetitle text-center mb-2">
            <h4 class="fw-bold text-black">DÉCOUVRER LES ANNONCES</h4>
        </div>
        <aside class="col-12 col-md-3 mb-3 d-none d-md-block">
            @include('abonné.include.partials.sidebar')
        </aside>

        <aside class="col-12 col-md-3 mb-3 d-md-none">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle px-2 py-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <span><i class="bi bi-funnel"></i>Filtrer et trier </span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @include('abonné.include.partials.sidebar')
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

@if ($biens->total() > $biens->perPage())
<nav aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item {{ $biens->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $biens->previousPageUrl() }}" aria-label="Précédent">
                <i class="bi bi-chevron-left"></i> Précédent
            </a>
        </li>

        @for ($i = 1; $i <= $biens->lastPage(); $i++)
        <li class="page-item {{ $i == $biens->currentPage() ? 'active' : '' }}">
            <a class="page-link" href="{{ $biens->url($i) }}">{{ $i }}</a>
        </li>
        @endfor

        <li class="page-item {{ $biens->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $biens->nextPageUrl() }}" aria-label="Suivant">
                Suivant <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 2,
        spaceBetween: 10,
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 4 },
        }
    });
</script>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        padding: 0;
        list-style: none;
    }

    .page-item {
        margin: 0 4px;
        transition: transform 0.2s ease-in-out;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        color: #007bff;
        border: 1px solid #007bff;
        background: white;
        transition: all 0.3s ease-in-out;
    }

    .page-link:hover {
        background: #007bff;
        color: white;
        transform: scale(1.1);
        box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.3);
    }

    .page-item.active .page-link {
        background: #007bff;
        color: white;
        border: 1px solid #0056b3;
        box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.5);
    }

    .page-item.disabled .page-link {
        background: #f0f0f0;
        color: #b0b0b0;
        border: 1px solid #ddd;
        pointer-events: none;
    }

    /* Supprime la flèche du dropdown */
    .dropdown-toggle::after {
        display: none !important;
    }

    .bi {
        font-size: 16px;
        margin: 0 5px;
    }

    @media (max-width: 576px) {
        .pagination {
            flex-wrap: wrap;
        }

        .page-link {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>

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
