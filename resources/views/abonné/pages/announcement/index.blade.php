<div class="mt-3">
    @if($biens->isEmpty())
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
        <div class="alert alert-info text-center text-black">
            Vous n'avez pas encore créé ou publié d'annonces.
        </div>
    </div>
    @else

    <ul class="nav nav-tabs d-flex justify-content-center" id="annonceTabs" role="tablist">
        <div class="row">
            <div class="col-auto">
                <li class="nav-item flex-fill py-2 me-2" role="presentation">
                    <a class="nav-link active" id="publie-tab" data-bs-toggle="tab" href="#publie" role="tab" aria-controls="publie" aria-selected="false">Publiées</a>
                </li>
            </div>
            <div class="col-auto">
                <li class="nav-item flex-fill py-2 me-2" role="presentation">
                    <a class="nav-link" id="brouillon-tab" data-bs-toggle="tab" href="#brouillon" role="tab" aria-controls="brouillon" aria-selected="true">Enrégistrées</a>
                </li>
            </div>
            <div class="col-auto">
                <li class="nav-item flex-fill py-2 me-2" role="presentation">
                    <a class="nav-link" id="annule-tab" data-bs-toggle="tab" href="#annule" role="tab" aria-controls="annule" aria-selected="false">Annulées</a>
                </li>
            </div>
        </div>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="publie" role="tabpanel" aria-labelledby="publie-tab">
            <div class="row">
                @php
                    $hasPublishedOrBlocked = false;
                @endphp
                @foreach($biens as $bien)
                    @if($bien->statut == 'publié' || $bien->statut == 'bloqué')
                        @php
                            $hasPublishedOrBlocked = true;
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                                <div class="position-relative">
                                    @if($bien->photos && count($bien->photos) > 0)
                                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                                             class="card-img-top"
                                             alt="Image de l'annonce">
                                    @else
                                        <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}"
                                             class="card-img-top"
                                             alt="Image par défaut">
                                    @endif
                                    <span class="badge bg-black position-absolute top-0 start-0 m-2 p-2" style="opacity: 70%">
                            {{ $bien->categorieBien->titre ?? 'Non spécifié' }}
                        </span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    @if($bien->statut == 'bloqué')
                                        <h5 class="card-title fw-bold text-black-50 text-truncate"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Annonce bloquée</h5>
                                    @else
                                        <h5 class="card-title fw-bold text-black-50 text-truncate">{{ Str::limit($bien->titre, 20, '...') }}</h5>
                                    @endif
                                    <p class="card-text text-muted mb-3">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($bien->lieu !== null ? $bien->lieu : 'N/A', 20, '...') }}<br>
                                        <strong>{{ Str::limit($bien->prix !== null ? number_format($bien->prix, 0, ',', ' ') : '0', 10, '...') }} FCFA </strong>
                                    </p>
                                    <div class="row justify-content-end">
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="actionMenu{{ $bien->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $bien->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('announcement.show', $bien->id) }}" title="Détails de cette annonce">
                                                            <i class="bi bi-eye"></i> Voir détails
                                                        </a>
                                                    </li>
                                                    @if($bien->statut != 'bloqué')
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('announcement.edit', $bien->id) }}" title="Modifier cette annonce">
                                                                <i class="bi bi-pencil-square"></i> Modifier annonce
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item delete-button" data-bs-toggle="modal" data-bs-target="#terminateConfirmation{{ $bien->id }}" title="Annuler cette annonce">
                                                                <i class="bi bi-x-circle"></i> Annuler annonce
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="terminateConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="terminateConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header yes">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <h5 class="modal-title" id="terminateConfirmationLabel{{ $bien->id }}">Confirmation d'annulation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-black">
                                        Êtes-vous sûr de vouloir arrêter cette annonce ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                        <form action="{{ route('announcement.terminate', $bien->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger"><i class="bi bi-check"></i> Valider</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if(!$hasPublishedOrBlocked)
                    <div class="col-12 mb-4">
                        <div class="alert alert-info text-center text-black">
                            Vous n'avez pas encore publié d'annonces.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="brouillon" role="tabpanel" aria-labelledby="brouillon-tab">
            <div class="row">
                @php
                    $hasDraft = false;
                @endphp
                @foreach($biens as $bien)
                    @if($bien->statut == 'brouillon')
                        @php
                            $hasDraft = true;
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                                <div class="position-relative">
                                    @if($bien->photos && count($bien->photos) > 0)
                                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                                             class="card-img-top"
                                             alt="Image de l'annonce">
                                    @else
                                        <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}"
                                             class="card-img-top"
                                             alt="Image par défaut">
                                    @endif
                                    <span class="badge bg-black position-absolute top-0 start-0 m-2 p-2" style="opacity: 70%">
                                        {{ $bien->categorieBien->titre ?? 'Non spécifié' }}
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-black-50 text-truncate">{{ Str::limit($bien->titre, 20, '...') }}</h5>
                                    <p class="card-text text-muted mb-3">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($bien->lieu !== null ? $bien->lieu : 'N/A', 20, '...') }}<br>
                                        <strong>{{ Str::limit($bien->prix !== null ? number_format($bien->prix, 0, ',', ' ') : '0', 10, '...') }} FCFA </strong>
                                    </p>
                                    <div class="row justify-content-end">
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="actionMenu{{ $bien->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $bien->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('announcement.show', $bien->id) }}" title="Détails de cette annonce">
                                                            <i class="bi bi-eye"></i> Voir détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('announcement.edit', $bien->id) }}" title="Modifier cette annonce">
                                                            <i class="bi bi-pencil-square"></i> Modifier annonce
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#publishConfirmation{{ $bien->id }}" title="Publier cette annonce">
                                                            <i class="bi bi-check-circle"></i> Publier annonce
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item delete-button" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $bien->id }}" title="Supprimer cette annonce">
                                                            <i class="bi bi-trash"></i> Supprimer annonce
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de confirmation -->
                        <div class="modal fade" id="deleteConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header yes">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <h5 class="modal-title" id="deleteConfirmationLabel{{ $bien->id }}">Confirmation de Suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-black">
                                        Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.
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

                        <!-- Modal de confirmation pour la publication -->
                        <div class="modal fade" id="publishConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="publishConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header yes">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <h5 class="modal-title" id="publishConfirmationLabel{{ $bien->id }}">Confirmation de Publication</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-black">
                                        Êtes-vous sûr de vouloir publier cette annonce ? Cette annonce sera disponible sur la plateforme.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                        <form action="{{ route('announcement.publish', $bien->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Publier</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if(!$hasDraft)
                    <div class="col-12 mb-4">
                        <div class="alert alert-info text-center text-black">
                            Vous n'avez aucune annonce enregistrée.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="annule" role="tabpanel" aria-labelledby="annule-tab">
            <div class="row">
                @php
                    $hasFinished = false;
                @endphp
                @foreach($biens as $bien)
                    @if($bien->statut == 'terminé')
                        @php
                            $hasFinished = true;
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                                <div class="position-relative">
                                    @if($bien->photos && count($bien->photos) > 0)
                                        <img src="{{ asset($bien->photos[0]->url_photo) }}"
                                             class="card-img-top"
                                             alt="Image de l'annonce">
                                    @else
                                        <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}"
                                             class="card-img-top"
                                             alt="Image par défaut">
                                    @endif
                                    <span class="badge bg-black position-absolute top-0 start-0 m-2 p-2" style="opacity: 70%">
                                        {{ $bien->categorieBien->titre ?? 'Non spécifié' }}
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-danger text-truncate">{{ Str::limit($bien->titre, 20, '...') }}</h5>
                                    <p class="card-text text-muted mb-3">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($bien->lieu !== null ? $bien->lieu : 'N/A', 10, '...') }}<br>
                                        <strong>{{ Str::limit($bien->prix !== null ? number_format($bien->prix, 0, ',', ' ') : '0', 20, '...') }} FCFA </strong>
                                    </p>
                                    <div class="row justify-content-end">
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="actionMenu{{ $bien->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $bien->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('announcement.show', $bien->id) }}" title="Détails cette annonce">
                                                            <i class="bi bi-eye"></i> Voir détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('announcement.edit', $bien->id) }}" title="Modifier cette annonce">
                                                            <i class="bi bi-pencil-square"></i> Modifier annonce
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#relaunchConfirmation{{ $bien->id }}" title="Republier cette annonce">
                                                            <i class="bi bi-check-circle"></i> Republier annonce
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item delete-button" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $bien->id }}" title="Supprimer cette annonce">
                                                            <i class="bi bi-trash"></i> Supprimer annonce
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de confirmation pour la suppression -->
                        <div class="modal fade" id="deleteConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header yes">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <h5 class="modal-title" id="deleteConfirmationLabel{{ $bien->id }}">Confirmation de Suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-black">
                                        Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.
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

                        <!-- Modal de confirmation pour la republication -->
                        <div class="modal fade" id="relaunchConfirmation{{ $bien->id }}" tabindex="-1" aria-labelledby="relaunchConfirmationLabel{{ $bien->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header yes">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <h5 class="modal-title" id="relaunchConfirmationLabel{{ $bien->id }}">Confirmation de Republication</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-black">
                                        Êtes-vous sûr de vouloir republier cette annonce ? Cette annonce sera à noouveau disponible sur la plateforme.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                        <form action="{{ route('announcement.relunch', $bien->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Republier</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif
                @endforeach
                @if(!$hasFinished)
                    <div class="col-12 mb-4">
                        <div class="alert alert-info text-center text-black">
                            Vous n'avez aucune annonce terminée.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

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

    .row.g-4 > [class*='col-'] {
        margin-bottom: 30px;
    }

    .yes{
        background-color: #007bff;
    }

    .btn {
        font-size: 0.9rem;
        padding: 8px 12px;
        white-space: nowrap;
    }

    /* Supprime la flèche du dropdown */
    .dropdown-toggle::after {
        display: none !important;
    }
</style>
