@extends('abonné.include.layouts.ap')

@section('content')
    <form id="createTicketForm" action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return showModal()">
        @csrf
        <h3 class="text-black-50 mb-4">Signaler votre problème</h3>

        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header text-black">
                <h5>Détails du problème</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="category" class="form-label text-black">Quel est le problème ?<span class="text-danger" title="obligatoire">*</span></label>
                    <select class="form-select form-control form-select-sm" id="category" name="id_categorie" required>
                        <option value="" disabled selected>Choisissez une catégorie...</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->nom_categorie }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label text-black">Objet<span class="text-danger" title="obligatoire">*</span></label>
                    <input type="text" name="titre" id="title" class="form-control" placeholder="Ex. : Un bug, problème d'annonce, etc." maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label for="motif" class="form-label text-black">Description<span class="text-danger" title="obligatoire">*</span></label>
                    <textarea name="description" id="motif" rows="4" class="form-control" placeholder="Décrivez un peu le problème en question..." maxlength="200" required></textarea>
                    <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label text-black">Capture d'écran</label>
                    <input type="file" name="piece_jointe" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Vous pouvez joindre une capture d'écran du problème en question.</small>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-send"></i> Envoyer
                    </button>
                </div>

            </div>
        </div>

    </form>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="confirmationModalLabel">Confirmation d'envoi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-black">
                    Êtes-vous sûr de vouloir signaler ce problème ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmSend"><i class="bi bi-check-circle"></i> Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            event.preventDefault();
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();

            document.getElementById('confirmSend').onclick = function() {
                showLoading();
                document.getElementById('createTicketForm').submit();
            }
        }
    </script>

@endsection
