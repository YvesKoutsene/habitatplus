@extends('abonné.include.layouts.app')
@section('content')

@if($biens->isEmpty())
<div class="alert alert-info text-center text-black">
    Aucune annonce disponible pour le moment.
</div>
@else
<div class="container-fluid px-3">
    <div class="pagetitle text-center mb-2">
        <p class="text-muted text-start">Top Annonces</p>
    </div>

    <div class="row">
        @foreach($biens as $bien)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                <div class="position-relative">
                    @if($bien->photos && count($bien->photos) > 0)
                    <img src="{{ asset($bien->photos[0]->url_photo) }}" class="card-img-top" alt="Image de l'annonce">
                    @else
                    <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="card-img-top" alt="Image par défaut">
                    @endif
                    <span class="badge bg-black position-absolute top-0 start-0 m-2 p-2" style="opacity: 70%">
                        {{ $bien->categorieBien->titre ?? 'Non spécifié' }}
                    </span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-black-50 text-truncate">{{ Str::limit($bien->titre, 20, '...') }}</h5>
                    <p class="card-text text-muted mb-3">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($bien->lieu, 20, '...') }}<br>
                        <strong>{{ number_format($bien->prix, 0, ',', ' ') }} FCFA</strong>
                    </p>
                    <a href="{{ route('announcement.show.costumer', $bien->id) }}" class="btn btn-outline-primary mt-auto">
                        <i class="bi bi-eye"></i> Détails
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
        border-bottom: 3px solid #007bff;
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .badge {
        font-size: 0.85rem;
        border-radius: 8px;
    }

    .like-icon {
        background-color: #ffffff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
    }

    .like-icon:hover {
        transform: scale(1.2);
    }

    .card-title {
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .btn {
        font-size: 0.9rem;
        padding: 8px 12px;
        white-space: nowrap;
    }

</style>

@endsection
