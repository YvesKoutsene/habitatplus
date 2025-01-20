<div class="container mt-4">
    <h2 class="mb-4 text-black-50">Mes Annonces</h2>
    @if($biens->isEmpty())
    <div class="alert alert-info text-center text-black">
        Vous n'avez pas encore créé ou publié d'annonces.
    </div>
    @else
    <!-- Onglets -->
    <ul class="nav nav-tabs" id="annonceTabs" role="tablist">
        <li class="nav-item flex-fill" role="presentation">
            <a class="nav-link active" id="brouillon-tab" data-bs-toggle="tab" href="#brouillon" role="tab" aria-controls="brouillon" aria-selected="true">Brouillon</a>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <a class="nav-link" id="publie-tab" data-bs-toggle="tab" href="#publie" role="tab" aria-controls="publie" aria-selected="false">Publié</a>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <a class="nav-link" id="annule-tab" data-bs-toggle="tab" href="#annule" role="tab" aria-controls="annule" aria-selected="false">Terminé</a>
        </li>
    </ul>
    <!-- Contenu des Onglets -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="brouillon" role="tabpanel" aria-labelledby="brouillon-tab">
            <div class="row">
                @foreach($biens as $bien)
                @if($bien->statut == 'brouillon')
                <div class="col-md-4 mb-3">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                        @if($bien->photos && count($bien->photos) > 0)
                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                             class="card-img-top"
                             alt="Image de l'annonce">
                        @else
                        <img src="{{ asset('images/default-image-annonce.png') }}"
                             class="card-img-top"
                             alt="Image par défaut">
                        @endif

                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">{{ Str::limit($bien->titre, 10, '...') }}</h5>
                            <p class="card-text mb-3">
                                <i class="bi bi-cash-stack"></i> <strong>Prix :</strong>
                                {{ Str::limit(number_format($bien->prix, 0, ',', ' '), 10, '...') }} FCFA<br>
                                <i class="bi bi-geo-alt-fill"></i> <strong>Lieu :</strong> {{ Str::limit($bien->lieu, 10, '...') }}
                            </p>
                        </div>

                        <div class="card-footer text-center">
                            <div class="row justify-content-center">
                                <div class="col-4 mb-2">
                                    <button class="btn btn-primary btn-block shadow-sm" title="Modifier">
                                        <i class="bi bi-pencil-square me-2"></i>
                                    </button>
                                </div>
                                <div class="col-4 mb-2">
                                    <button type="button" class="btn btn-warning btn-block shadow-sm delete-button" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $bien->id }}" title="Supprimer">
                                        <i class="bi bi-trash me-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="deleteConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $bien->id }}">Confirmation de Suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-black">
                                Êtes-vous sûr de vouloir supprimer cette annonce" ? Cette action est irréversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                <form action="{{ route('announcement.destroy', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade" id="publie" role="tabpanel" aria-labelledby="publie-tab">
            <div class="row">
                @foreach($biens as $bien)
                @if($bien->statut == 'publié' || $bien->statut == 'republié')
                <div class="col-md-4 mb-3">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                        @if($bien->photos && count($bien->photos) > 0)
                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                             class="card-img-top"
                             alt="Image de l'annonce">
                        @else
                        <img src="{{ asset('images/default-image-annonce.png') }}"
                             class="card-img-top"
                             alt="Image par défaut">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">{{ Str::limit($bien->titre, 10, '...') }}</h5>
                            <p class="card-text mb-3">
                                <i class="bi bi-cash-stack"></i> <strong>Prix :</strong>
                                {{ Str::limit(number_format($bien->prix, 0, ',', ' '), 10, '...') }} FCFA<br>
                                <i class="bi bi-geo-alt-fill"></i> <strong>Lieu :</strong> {{ Str::limit($bien->lieu, 10, '...') }}
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row justify-content-center">
                                <div class="col-4 mb-2">
                                    <button class="btn btn-primary btn-block shadow-sm" title="Modifier">
                                        <i class="bi bi-pencil-square me-2"></i>
                                    </button>
                                </div>
                                <div class="col-4 mb-2">
                                    <button type="button" class="btn btn-warning btn-block shadow-sm delete-button" data-bs-toggle="modal" data-bs-target="#terminateConfirmation{{ $bien->id }}" title="Arrêté">
                                        <i class="bi bi-x-circle me-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="terminateConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="terminateConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <h5 class="modal-title" id="terminateConfirmationLabel{{ $bien->id }}">Confirmation d'arrêt</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-black">
                                Êtes-vous sûr de vouloir arrêter cette annonce" ?.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                <form action="{{ route('announcement.terminate', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-check"></i> Arrêté</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade" id="annule" role="tabpanel" aria-labelledby="annule-tab">
            <div class="row">
                @foreach($biens as $bien)
                @if($bien->statut == 'terminé')
                <div class="col-md-4 mb-3">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                        @if($bien->photos && count($bien->photos) > 0)
                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                             class="card-img-top"
                             alt="Image de l'annonce">
                        @else
                        <img src="{{ asset('images/default-image-annonce.png') }}"
                             class="card-img-top"
                             alt="Image par défaut">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">{{ Str::limit($bien->titre, 10, '...') }}</h5>
                            <p class="card-text mb-3">
                                <i class="bi bi-cash-stack"></i> <strong>Prix :</strong>
                                {{ Str::limit(number_format($bien->prix, 0, ',', ' '), 10, '...') }} FCFA<br>
                                <i class="bi bi-geo-alt-fill"></i> <strong>Lieu :</strong> {{ Str::limit($bien->lieu, 10, '...') }}
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row justify-content-center">
                                <div class="col-4 mb-2">
                                        <button type="button" class="btn btn-primary btn-block shadow-sm delete-button" data-bs-toggle="modal" data-bs-target="#relaunchConfirmation{{ $bien->id }}" title="Republier">
                                        <i class="bi bi-check me-2"></i>
                                    </button>
                                </div>
                                <div class="col-4 mb-2">
                                    <button type="button" class="btn btn-warning btn-block shadow-sm delete-button" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $bien->id }}" title="Supprimer">
                                        <i class="bi bi-trash me-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="deleteConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <h5 class="modal-title" id="deleteConfirmationLabel{{ $bien->id }}">Confirmation de Suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-black">
                                Êtes-vous sûr de vouloir supprimer cette annonce " ? Cette action est irréversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                <form action="{{ route('announcement.destroy', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="relaunchConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="relaunchConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <h5 class="modal-title" id="relaunchConfirmationLabel{{ $bien->id }}">Confirmation de Rupublication</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-black">
                                Êtes-vous sûr de vouloir republier cette annonce" ?.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                <form action="{{ route('announcement.relaunch', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Republié</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-height: 400px;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 150px;
        object-fit: cover;
    }

    .btn {
        border-radius: 25px;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }
</style>
