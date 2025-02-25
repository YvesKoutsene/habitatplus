@extends('admin.include.layouts.app')
@section('content')

@php
    use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Ticket</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Categorie Ticket</li>
            <li class="breadcrumb-item active">Liste Categorie</li>
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
                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories ticket'))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="bi bi-plus-circle"></i> Ajouter catégorie
                            </button>
                        @endif
                    </div>

                    @if(!$categories->isEmpty())
                        <div class="d-flex mb-3 justify-content-between">
                            <form action="{{ route('category_ticket.index') }}" method="GET" class="d-flex">
                                <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                                </select>
                            </form>
                            <form action="{{ route('category_ticket.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    @endif

                    @if($categories->isEmpty())
                        <div class="alert alert-info">
                            Aucune catégorie de ticket disponible pour le moment.
                            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories ticket'))
                                <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Ajouter catégorie</button>
                            @endif
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Description</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Date création</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $categorie)
                            <tr>
                                <td>{{ $categorie->id }}</td>
                                <td>{{ ucfirst($categorie->nom_categorie) }}</td>
                                <td>
                                    @if(strlen($categorie->description) > 8)
                                    {{ ucfirst(substr($categorie->description, 0, 8)) }}...
                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $categorie->id }}">
                                        Lire suite
                                    </button>
                                    <div class="modal fade" id="descriptionModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $categorie->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $categorie->id }}">Description complète de la catégorie {{ ucfirst($categorie->nom_categorie) }}</h5>
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
                                    {{ ucfirst($model->description) }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $categorie->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($categorie->statut) }}
                                    </span>
                                </td>
                                <td>{{ Carbon::parse($categorie->created_at)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('désactiver/réactiver catégories ticket'))
                                            @if($categorie->statut == 'actif')
                                                <form action="{{ route('category_ticket.suspend', $categorie->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Désactiver">
                                                        <i class="bi bi-slash-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('category_ticket.reactivate', $categorie->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Réactiver">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('editer catégories ticket'))
                                            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $categorie->id }}">
                                                <i class="bi bi-pencil-square" title="Editer"></i>
                                            </button>
                                        @endif
                                        @if (Auth::user()->typeUser === 0 || Auth::user()->can('supprimer catégories ticket'))
                                            @if ($categorie->tickets->isEmpty())
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $categorie->id }}">
                                                <i class="bi bi-trash" title="Supprimer"></i>
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
                                                        Êtes-vous sûr de vouloir supprimer la catégorie de ticket "{{ $categorie->nom_categorie }}" ? Cette action est irréversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                        <form action="{{ route('category_ticket.destroy', $categorie->id) }}" method="POST">
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

                            <div class="modal fade" id="editCategoryModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $categorie->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryModalLabel{{ $categorie->id }}">Modifier Catégorie</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('category_ticket.update', $categorie->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="nom_categorie{{ $categorie->id }}" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                                                    <input type="hidden" name="id" value="{{ $categorie->id }}">
                                                    <input type="text" name="nom_categorie" class="form-control" placeholder="Nom de categorie de ticket" id="nom_categorie{{ $categorie->id }}" value="{{ $categorie->nom_categorie }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                                    <textarea
                                                        name="description"
                                                        id="description"
                                                        class="form-control"
                                                        rows="3"
                                                        maxlength="200"
                                                        placeholder="Ajoutez une description"
                                                        required>{{ old('description',  $categorie->description) }}</textarea>
                                                    <div class="invalid-feedback">
                                                        Veuillez fournir une description valide.
                                                    </div>
                                                    <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Retour</button>
                                                    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Mettre à jour</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Description</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Date création</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
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

@if(request()->get('showModal') === 'create')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('createCategoryModal'), {
                keyboard: false
            });
            myModal.show();
        });
    </script>
@endif

<!-- Modal pour ajouter un paramètre -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Ajouter Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category_ticket.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nom_categorie" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                        <input type="text" name="nom_categorie" class="form-control" id="nom_categorie" required placeholder="Nom de categorie de ticket">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description<span class="text-danger" title="obligatoire">*</span></label>
                        <textarea
                            name="description"
                            id="description"
                            class="form-control"
                            rows="3"
                            maxlength="200"
                            placeholder="Ajoutez une description" required></textarea>
                        <div class="invalid-feedback">
                            Veuillez fournir une description valide.
                        </div>
                        <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
