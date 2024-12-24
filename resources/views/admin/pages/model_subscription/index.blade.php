@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Modèles d'Abonnement</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Abonnements</li>
            <li class="breadcrumb-item active">Liste Modèles</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des Modèles d'Abonnement</h5>
                        <a href="{{ route('subscription_model.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Créer modèle
                        </a>
                    </div>

                    @if($subscriptionModels->isEmpty())
                        <div class="alert alert-info">
                            Aucun modèle d'abonnement disponible pour le moment. <a href="{{ route('subscription_model.create') }}" class="alert-link">Créer modèle</a>.
                        </div>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Durée (Mois)</th>
                                    <th>Paramètres associés</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptionModels as $model)
                                    <tr>
                                        <td>{{ $model->id }}</td>
                                        <td>{{ ucfirst($model->name) }}</td>
                                        <td>
                                            @if(strlen($model->description) > 8)
                                                {{ ucfirst(substr($model->description, 0, 8)) }}...
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $model->id }}">
                                                    Lire la suite
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="descriptionModal{{ $model->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $model->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="descriptionModalLabel{{ $model->id }}">Description complète de {{ ucfirst($model->name) }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ ucfirst($model->description) }}
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
                                        <td>{{ number_format($model->price, 0, ',', ' ') }}</td>
                                        <td>{{ $model->duration }}</td>
                                        <td>
                                            @foreach($model->parameters->take(2) as $parameter)
                                            <span class="badge bg-success">{{ $parameter->name }} : {{ $parameter->value }}</span>
                                            @endforeach
                                            @if($model->parameters->count() > 2)
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametersModal{{ $model->id }}">
                                                    Voir plus ({{ $model->parameters->count() }})
                                                </button>

                                                <!-- Modal pour afficher tous les paramètres -->
                                                <div class="modal fade" id="parametersModal{{ $model->id }}" tabindex="-1" aria-labelledby="parametersModalLabel{{ $model->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="parametersModalLabel{{ $model->id }}">Paramètres de {{ ucfirst($model->name) }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="list-group">
                                                                    @foreach($model->parameters as $parameter)
                                                                        <li class="list-group-item">{{ $parameter->name }} : {{ $parameter->value }}</li>
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
                                        <td>{{ \Carbon\Carbon::parse($model->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('subscription_model.edit', $model->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $model->id }}" data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteConfirmation{{ $model->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $model->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $model->id }}">Confirmation de Suppression</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer le modèle d'abonnement "{{ $model->name }}" ? Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <form action="{{ route('subscription_model.destroy', $model->id) }}" method="POST" class="d-inline">
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
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Durée (Mois)</th>
                                    <th>Paramètres associés & Valeur</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination personnalisée -->
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $subscriptionModels->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $subscriptionModels->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $subscriptionModels->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $subscriptionModels->lastPage(); $i++)
                                    <li class="page-item {{ $i == $subscriptionModels->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $subscriptionModels->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $subscriptionModels->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $subscriptionModels->nextPageUrl() }}">Suivant</a>
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
