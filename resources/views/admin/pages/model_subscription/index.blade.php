@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Modèles</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Modèle Abonnement</li>
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
                        <h5 class="card-title">Liste des Modèles</h5>
                        <a href="{{ route('model_subscription.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Créer modèle
                        </a>
                    </div>

                    @if($modeles->isEmpty())
                        <div class="alert alert-info">
                            Aucun modèle d'abonnement disponible pour le moment. <a href="{{ route('model_subscription.create') }}" class="alert-link">Créer modèle</a>.
                        </div>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Durée</th>
                                    <th>Description</th>
                                    <th>Paramètres/valeurs</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modeles as $model)
                                    <tr>
                                        <td>{{ $model->id }}</td>
                                        <td>{{ ucfirst($model->nom) }}</td>
                                        <td>{{ number_format($model->prix, 0, ',', ' ') }}</td>
                                        <td>{{ $model->duree }}</td>
                                        <td>
                                            @if(strlen($model->description) > 8)
                                                {{ ucfirst(substr($model->description, 0, 8)) }}...
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $model->id }}">
                                                    Lire suite
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="descriptionModal{{ $model->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $model->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="descriptionModalLabel{{ $model->id }}">Description complète du modèle {{ ucfirst($model->nom) }}</h5>
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
                                        <td>
                                            @if($model->parametresAvecValeurs->isNotEmpty())
                                                @foreach($model->parametresAvecValeurs->take(1) as $association)
                                                    @if($association->parametre)
                                                        <span class="badge bg-success">
                                                            {{ $association->parametre->nom_parametre }} 
                                                        </span> :
                                                        <span class="badge bg-warning">
                                                            {{ $association->valeurs->first() ? $association->valeurs->first()->valeur : 'Aucune valeur' }}
                                                        </span>
                                                    @endif
                                                @endforeach

                                                @if($model->parametresAvecValeurs->count() > 1)
                                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametersModal{{ $model->id }}">
                                                        Voir plus ({{ $model->parametresAvecValeurs->count() }})
                                                    </button>

                                                    <!-- Modal pour afficher tous les paramètres -->
                                                    <div class="modal fade" id="parametersModal{{ $model->id }}" tabindex="-1" aria-labelledby="parametersModalLabel{{ $model->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">  <!--modal-lg-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="parametersModalLabel{{ $model->id }}">Paramètres du modèle {{ ucfirst($model->nom) }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <ul class="list-group">
                                                                        @foreach($model->parametresAvecValeurs as $association)
                                                                            @if($association->parametre)
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                    {{ $association->parametre->nom_parametre }} 
                                                                                    <span class="badge bg-primary rounded-pill">
                                                                                    {{ $association->valeurs->first() ? $association->valeurs->first()->valeur : 'Aucune valeur' }}
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
                                                    <!-- Fin de la modal -->
                                                @endif
                                            @else
                                                <span class="text-muted">Aucun paramètre associé</span>
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($model->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('model_subscription.edit', $model->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                
                                                @if ($model->transactions->isEmpty())
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $model->id }}" data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteConfirmation{{ $model->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $model->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $model->id }}">Confirmation de Suppression</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer le modèle d'abonnement "{{ $model->nom }}" ? Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <form action="{{ route('model_subscription.destroy', $model->id) }}" method="POST" class="d-inline">
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
                                    <th>Prix (FCFA)</th>
                                    <th>Durée</th>
                                    <th>Description</th>
                                    <th>Paramètres/valeurs</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination personnalisée -->
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $modeles->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $modeles->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $modeles->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $modeles->lastPage(); $i++)
                                    <li class="page-item {{ $i == $modeles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $modeles->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $modeles->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $modeles->nextPageUrl() }}">Suivant</a>
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
