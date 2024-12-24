@extends('admin.include.layouts.app')
@section('content')

@php
    use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Modèles</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Modele Abonnement</li>
            <li class="breadcrumb-item active">Liste Paramètres</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des Paramètres</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createParameterModal">
                            <i class="bi bi-plus-circle"></i> Ajouter paramètre
                        </button>
                    </div>

                    @if($parametres->isEmpty())
                        <div class="alert alert-info">
                            Aucun paramètre disponible pour le moment. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#createParameterModal">Ajouter paramètre</button>.
                        </div>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Date de Création</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parametres as $parametre)
                                    <tr>
                                        <td>{{ $parametre->id }}</td>
                                        <td>{{ ucfirst($parametre->nom_parametre) }}</td>
                                        <td>{{ Carbon::parse($parametre->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editParameterModal{{ $parametre->id }}">
                                                    <i class="bi bi-pencil-square" title="Editer"></i>
                                                </button>
                                                @if ($parametre->associations->isEmpty())
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $parametre->id }}">
                                                        <i class="bi bi-trash" title="Supprimer"></i>
                                                    </button>
                                                @endif
                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteConfirmation{{ $parametre->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $parametre->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $parametre->id }}">Confirmation de Suppression</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer le paramètre "{{ $parametre->nom_parametre }}" ? Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <form action="{{ route('parameter_category.destroy', $parametre->id) }}" method="POST">
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

                                    <!-- Modal pour modifier un paramètre -->
                                    <div class="modal fade" id="editParameterModal{{ $parametre->id }}" tabindex="-1" aria-labelledby="editParameterModalLabel{{ $parametre->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editParameterModalLabel{{ $parametre->id }}">Modifier Paramètre</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('parameter_model.update', $parametre->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="nom_parametre{{ $parametre->id }}" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                                                            <input type="hidden" name="id" value="{{ $parametre->id }}">
                                                            <input type="text" name="nom_parametre" class="form-control" placeholder="Nom du paramètre de model" id="nom_parametre{{ $parametre->id }}" value="{{ $parametre->nom_parametre }}" required>
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
  
                                    <!-- Fin du modal -->
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Date de Création</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination personnalisée -->
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $parametres->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $parametres->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $parametres->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $parametres->lastPage(); $i++)
                                    <li class="page-item {{ $i == $parametres->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $parametres->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $parametres->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $parametres->nextPageUrl() }}">Suivant</a>
                                </li>
                            </ul>
                        </nav>

                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal pour ajouter un paramètre -->
<div class="modal fade" id="createParameterModal" tabindex="-1" aria-labelledby="createParameterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createParameterModalLabel">Ajouter Paramètre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('parameter_model.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nom_parametre" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                        <input type="text" name="nom_parametre" class="form-control" id="nom_parametre" required placeholder="Nom du paramètre de modele">
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
<!-- Fin du modal -->

@endsection
