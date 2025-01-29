@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Détails du Bien</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('announcement.list') }}">Liste des Annonces</a></li>
            <li class="breadcrumb-item active">Détails du Bien</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <img src="{{ asset($bien->user->photo_profil) }}" alt="Profil" class="rounded-circle border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                        <h5 class="card-title mb-0">{{ $bien->user->email }}</h5>
                        <small class="text-muted d-block">A crée cette annonce le {{ $bien->created_at->format('d M Y') }}.</small>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Photos du Bien</h5>
                    <div id="bienCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @forelse($bien->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset($photo->url_photo) }}" class="d-block w-100 rounded" alt="Photo du bien">
                            </div>
                            @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="d-block w-100 rounded" alt="Image par défaut">
                            </div>
                            @endforelse
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bienCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bienCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        @if($bien->photos && count($bien->photos) > 0)
                        @foreach($bien->photos as $index => $photo)
                        <img src="{{ asset($photo->url_photo) }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature" data-bs-target="#bienCarousel" data-bs-slide-to="{{ $index }}">
                        @endforeach
                        @else
                        <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature">
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Informations du Bien</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between"><strong>Titre :</strong> {{ $bien->titre }}</li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Type d'offre :</strong> {{ $bien->type_offre }}</li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Prix :</strong> {{ number_format($bien->prix, 0, ',', ' ') }} FCFA</li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Catégorie :</strong> {{ $bien->categorieBien->titre }}</li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Lieu :</strong> {{ $bien->lieu }}</li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Statut :</strong> <span class="badge {{ $classe = ($bien->statut == 'publié') ? 'bg-primary' : (($bien->statut == 'terminé') ? 'bg-warning' : 'bg-danger'); }}">{{ ucfirst($bien->statut) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span id="descriptionText" class="text-black-50">
                                {{ Str::limit($bien->description !== null ? $bien->description : 'Aucune description', 25) }}
                            </span>
                                @if(strlen($bien->description) > 25)
                            <button class="btn btn-link btn-sm" id="toggleDescription" style="text-decoration: none; color: #007bff;">
                                Voir plus
                            </button>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Caractéristiques du Bien</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($bien->valeurs as $valeur)
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">{{ $valeur->associationCategorie->parametre->nom_parametre }} :</span>
                            <span>{{ $valeur->valeur }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .carousel img {
        height: 400px;
        object-fit: cover;
    }

    .list-group-item {
        background-color: #fafafa;
        border: none;
        padding: 18px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #eaeaea;
    }

    .img-thumbnail {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, border 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        border: 2px solid #007bff;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 3rem;
        height: 3rem;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        border: 2px solid white;
    }

    .carousel-control-prev-icon::before,
    .carousel-control-next-icon::before {
        color: white;
        font-size: 1.5rem;
    }

    .carousel-control-prev:hover .carousel-control-prev-icon,
    .carousel-control-next:hover .carousel-control-next-icon {
        background-color: rgba(255, 255, 255, 0.8);
        color: black;
    }
</style>

<script>
    document.getElementById('toggleDescription')?.addEventListener('click', function() {
        var descriptionText = document.getElementById('descriptionText');
        var toggleButton = document.getElementById('toggleDescription');

        if (toggleButton.innerText === 'Voir plus') {
            descriptionText.innerText = '{{ $bien->description }}';
            toggleButton.innerText = 'Voir moins';
        } else {
            descriptionText.innerText = '{{ Str::limit($bien->description, 25) }}';
            toggleButton.innerText = 'Voir plus';
        }
    });
</script>
@endsection
