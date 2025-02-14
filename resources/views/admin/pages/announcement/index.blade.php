@extends('admin.include.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Annonces</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Annonces</li>
            <li class="breadcrumb-item active">Liste Annonce</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des Annonces</h5>
                    </div>
                    @if(!$biens->isEmpty())
                    <div class="d-flex mb-3 justify-content-between">
                        <form action="{{ route('announcement.list') }}" method="GET" class="d-flex">
                            <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                            </select>
                        </form>
                        <form action="{{ route('announcement.list') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    @endif

                    @if($biens->isEmpty())
                    <div class="alert alert-info">
                        Aucune annonce de bien disponible pour le moment.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Propriétaire</th>
                                <th>Titre annonce</th>
                                <th>Prix (FCFA)</th>
                                <th>Caractéristiques</th>
                                <th>Publié le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($biens as $bien)
                            <tr>
                                <td>
                                    <img src="{{ asset($bien->user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                    | {{ $bien->user->name }}
                                </td>
                                <td>
                                    @if(strlen($bien->titre) > 8)
                                    {{ ucfirst(substr($bien->titre, 0, 8)) }}...
                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $bien->id }}">
                                        Lire suite
                                    </button>
                                    <div class="modal fade" id="descriptionModal{{ $bien->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $bien->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $bien->id }}">Titre complet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ ucfirst($bien->titre) }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    {{ ucfirst($bien->titre) }}
                                    @endif
                                </td>
                                <td>{{ number_format($bien->prix, 0, ',', ' ') }}</td>
                                <td>
                                    @if($bien->valeurs->isNotEmpty())
                                    @foreach($bien->valeurs->take(1) as $valeur)
                                    @if($valeur)
                                    <span class="badge bg-success">
                                    {{ $valeur->associationCategorie->parametre->nom_parametre }}
                                </span> :
                                    <span class="badge bg-warning">
                                    {{ $valeur->valeur }}
                                </span>
                                    @endif
                                    @endforeach
                                    @if($bien->valeurs->count() > 1)
                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametersModal{{ $bien->id }}">
                                        Voir plus ({{ $bien->valeurs->count() - 1 }})
                                    </button>
                                    <div class="modal fade" id="parametersModal{{ $bien->id }}" tabindex="-1" aria-labelledby="parametersModalLabel{{ $bien->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="parametersModalLabel{{ $bien->id }}">Valeur du bien {{ ucfirst($bien->titre) }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        @foreach($bien->valeurs as $valeur)
                                                        @if($valeur)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $valeur->associationCategorie->parametre->nom_parametre }}
                                                            <span class="badge bg-primary rounded-pill">
                                                            {{ $valeur->valeur }}
                                                        </span>
                                                        </li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle"></i> Fermer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    <span class="text-muted">Aucune valeur associée</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($bien->datePublication)->translatedFormat('d F Y') }}</td>
                                <td>
                                <span class="badge {{ $classe = ($bien->statut == 'publié') ? 'bg-primary' : (($bien->statut == 'terminé') ? 'bg-warning' : 'bg-danger'); }}">
                                    {{ ucfirst($bien->statut) }}
                                </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir annonces'))
                                        <a href="{{ route('announcement.details', $bien->id) }}" class="btn btn-sm btn-outline-info me-2" title="Détails de l'annonce">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('suspendre/réactiver annonces'))
                                        @if($bien->statut == 'publié')
                                        <form action="{{ route('announcement.block', $bien->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Bloquer cette annonce">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                        </form>
                                        @elseif($bien->statut == 'bloqué')
                                        <form action="{{ route('announcement.reactivate', $bien->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Réactiver cette annonce">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endif
                                    </div
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Propriétaire</th>
                                <th>Titre annonce</th>
                                <th>Prix (FCFA)</th>
                                <th>Caractéristiques</th>
                                <th>Publié le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>


                    <nav aria-label="...">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{ $biens->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $biens->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $biens->onFirstPage() }}">Précédent</a>
                            </li>

                            @for ($i = 1; $i <= $biens->lastPage(); $i++)
                            <li class="page-item {{ $i == $biens->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $biens->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor
                            <li class="page-item {{ $biens->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $biens->nextPageUrl() }}">Suivant</a>
                            </li>
                        </ul>
                    </nav>

                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
