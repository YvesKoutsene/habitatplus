@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Catégories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
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
                        <a href="{{ route('category_bien.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Créer catégorie
                        </a>
                    </div>

                    @if($categories->isEmpty())
                        <div class="alert alert-info">
                            Aucune catégorie de bien disponible pour le moment. <a href="{{ route('category_bien.create') }}" class="alert-link">Créer catégorie</a>.
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
                                                    Lire la suite
                                                </button>

                                                <!-- Modal -->
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
                                            @foreach($categorie->associations->take(2) as $association)
                                            <span class="badge bg-success">{{ $association->parametre->nom_parametre }}</span>
                                            @endforeach
                                            @if($categorie->associations->count() > 2)
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametresModal{{ $categorie->id }}"> <!--class="btn btn-link"-->
                                                    Voir plus ({{ $categorie->associations->count() }})
                                                </button>

                                                <!-- Modal pour afficher tous les paramètres -->
                                                <div class="modal fade" id="parametresModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="parametresModalLabel{{ $categorie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
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
                                                <!-- Fin de la modal -->
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($categorie->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('category_bien.edit', $categorie->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $categorie->id }}" data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteConfirmation{{ $categorie->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $categorie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $categorie->id }}">Confirmation de Suppression</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer la catégorie "{{ $categorie->nom_categorie }}" ? Cette action est irréversible.
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
                                                <!-- Fin de la modal -->
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
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
