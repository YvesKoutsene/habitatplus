@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Catégories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Categories</li>
            <li class="breadcrumb-item active">Lste Catégorie</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des Catégories</h5>
                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories'))
                            <a href="{{ route('category_bien.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Créer catégorie
                            </a>
                        @endif
                    </div>

                    @if(!$categories->isEmpty())
                        <div class="d-flex mb-3 justify-content-between">
                            <form action="{{ route('category_bien.index') }}" method="GET" class="d-flex">
                                <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                                </select>
                            </form>
                            <form action="{{ route('category_bien.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    @endif
                    @if($categories->isEmpty())
                        <div class="alert alert-info">
                            Aucune catégorie de bien disponible pour le moment.
                            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories'))
                                <a href="{{ route('category_bien.create') }}" class="alert-link">Créer catégorie</a>
                            @endif
                        </div>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Paramètres associés</th>
                                    <th>Date création</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $categorie)
                                    <tr>
                                        <td>{{ $categorie->id }}</td>
                                        <td>{{ ucfirst($categorie->titre) }}</td>
                                        <td>
                                            @if(strlen($categorie->description) > 8)
                                                {{ ucfirst(substr($categorie->description, 0, 8)) }}...
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $categorie->id }}"> <!--class="btn btn-link"-->
                                                    Lire suite
                                                </button>
                                                <div class="modal fade" id="descriptionModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $categorie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="descriptionModalLabel{{ $categorie->id }}">Description complète de {{ ucfirst($categorie->titre) }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ ucfirst($categorie->description) }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                {{ ucfirst($categorie->description) }}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($categorie->associations->take(1) as $association)
                                            <span class="badge bg-success">{{ $association->parametre->nom_parametre }}</span>
                                            @endforeach
                                            @if($categorie->associations->count() > 1)
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametresModal{{ $categorie->id }}"> <!--class="btn btn-link"-->
                                                    Voir plus ({{ $categorie->associations->count() -1 }})
                                                </button>

                                                <div class="modal fade" id="parametresModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="parametresModalLabel{{ $categorie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog"> <!--modal-lg-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="parametresModalLabel{{ $categorie->id }}">Paramètres de {{ ucfirst($categorie->titre) }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="list-group">
                                                                    @foreach($categorie->associations as $association)
                                                                        <li class="list-group-item" >{{ $association->parametre->nom_parametre }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($categorie->created_at)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            <span class="badge {{ $categorie->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($categorie->statut) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if(Auth::user()->typeUser === 0 || Auth::user()->can('désactiver/réactiver catégories'))
                                                    @if($categorie->statut == 'actif')
                                                    <form action="{{ route('category_bien.suspend', $categorie->id) }}" method="POST" class="me-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Désactiver">
                                                            <i class="bi bi-slash-circle"></i>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('category_bien.reactivate', $categorie->id) }}" method="POST" class="me-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Réactiver">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                @endif
                                                @if($categorie->biens->isEmpty() && $categorie->alertes->isEmpty())
                                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('editer catégories'))
                                                    <a href="{{ route('category_bien.edit', $categorie->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" title="Modifier">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    @endif
                                                @endif

                                                @if($categorie->biens->isEmpty() && $categorie->alertes->isEmpty())
                                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('supprimer catégories'))
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $categorie->id }}" data-bs-toggle="tooltip" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endif
                                                @endif

                                                <div class="modal fade" id="deleteConfirmation{{ $categorie->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $categorie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $categorie->id }}">Confirmation de Suppression</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer la catégorie "{{ $categorie->titre }}" ? Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <form action="{{ route('category_bien.destroy', $categorie->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Paramètres associés</th>
                                    <th>Date création</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination personnalisée -->
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $categories->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $categories->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $categories->lastPage(); $i++)
                                    <li class="page-item {{ $i == $categories->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $categories->nextPageUrl() }}">Suivant</a>
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
