@extends('abonné.include.layouts.app')
@section('content')

@if($biens->isEmpty())
<div class="alert alert-info text-center text-black">
    Aucune annonce disponible pour le moment.
</div>
@else
<div class="container">
    <div class="pagetitle text-center mb-2">
        <p class="text-muted text-start">Top Annonces</p>
    </div>

    <div class="row">
        @foreach($biens as $bien)
        <div class="col-6 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                <!-- Image -->
                <div class="position-relative">
                    @if($bien->photos && count($bien->photos) > 0)
                    <img src="{{ asset($bien->photos[0]->url_photo) }}"
                         class="card-img-top"
                         alt="Image de l'annonce">
                    @else
                    <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}"
                         class="card-img-top"
                         alt="Image par défaut">
                    @endif
                    <span class="badge bg-primary position-absolute top-0 start-0 m-2 p-2">
                        {{ $bien->categorieBien->titre ?? 'Non spécifié' }}
                    </span>
                    <span class="like-icon position-absolute bottom-0 end-0 m-2 p-2">
                        <i class="bi bi-heart text-danger fs-5" title="Favoris"></i>
                    </span>
                </div>

                <!-- Card Content -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-danger text-truncate">{{ Str::limit($bien->titre, 20, '...') }}</h5>
                    <p class="card-text text-muted mb-3">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($bien->lieu, 30, '...') }}<br>
                        <strong>{{ number_format($bien->prix, 0, ',', ' ') }} FCFA</strong>
                    </p>
                    <a href="" class="btn btn-outline-primary mt-auto">
                        <i class="bi bi-eye"></i> Voir détails
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<style>
    .pagetitle h1 {
        font-size: 2.5rem;
        color: #333;
    }

    .pagetitle p {
        font-size: 1rem;
        color: #777;
    }

    .card {
        border: 1px solid #f0f0f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: linear-gradient(to bottom, #ffffff, #f9f9f9);
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .card-img-top {
        height: 220px;
        object-fit: cover;
        border-bottom: 3px solid #007bff;
    }

    .badge {
        font-size: 0.85rem;
        border-radius: 8px;
    }

    .like-icon {
        background-color: #ffffff;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .like-icon:hover {
        transform: scale(1.2);
    }

    .card-title {
        font-size: 1.2rem;
        line-height: 1.5;
    }

    .card-text {
        font-size: 0.95rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        font-size: 1rem;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .row.g-4 > [class*='col-'] {
        margin-bottom: 30px;
    }
</style>

@endsection
