@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Modèles</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('model_subscription.index') }}">Modèle Abonnement</a></li>
            <li class="breadcrumb-item active">Créer Modèle</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations sur le modèle</h5>

                    <form action="{{ route('model_subscription.store') }}" method="POST" class="needs-validation">
                        @csrf

                        <div class="mb-4">
                            <label for="nom_modele" class="form-label">Nom<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nom"
                                id="nom_modele"
                                class="form-control"
                                placeholder="Nom du modèle d'abonnement"
                                required>
                            <div class="invalid-feedback">
                                Veuillez fournir un nom pour le modèle.
                            </div>
                        </div>
                        <div>
                            <label for="prix_modele" class="form-label">Prix<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">FCFA</span>
                                <input
                                    type="number"
                                    name="prix"
                                    id="prix_modele"
                                    class="form-control"
                                    placeholder="Prix du modèle d'abonnement"
                                    required min="0" oninput="validateInput()">
                                <span class="input-group-text">.00</span>
                                <div class="invalid-feedback">
                                    Veuillez fournir un prix pour le modèle.
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="duree_modele" class="form-label">Duree<span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input
                                    type="number"
                                    name="duree"
                                    id="valeur03"
                                    class="form-control"
                                    placeholder="Durée du modèle d'abonnement"
                                    required min="0" oninput="validateInput04()">
                                    <span class="input-group-text">Mois</span>
                                <div class="invalid-feedback">
                                    Veuillez fournir une durée pour le modèle.
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="4"
                                maxlength="200"
                                placeholder="Ajoutez une description"
                                required></textarea>
                            <div class="invalid-feedback">
                                Veuillez fournir une description valide.
                            </div>
                            <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                        </div>

                        <!-- Paramètres de Catégorie -->
                        <div class="mb-4">
                            <div id="parametres-container">
                                <!-- Champ dynamique pour les paramètres -->
                                <div class="row g-3 mb-2 parametre-item">
                                    <div class="col-md-6">
                                        <label for="parametres[0][id]" class="form-label">Paramètre<span class="text-danger">*</span></label>
                                        <select name="parametres[0][id]" class="form-select" required>
                                            <option value="">-- Sélectionner un paramètre --</option>
                                            @foreach($parametres as $parametre)
                                                <option value="{{ $parametre->id }}">{{ $parametre->nom_parametre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parametres[0][valeur]" class="form-label">Valeur<span class="text-danger">*</span></label>
                                        <input type="number" name="parametres[0][valeur]" class="form-control" required min="0" oninput="validateInput02()" id="valeur" placeholder="Valeur du paramètre">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-parametre" disabled>Supprimer</button>
                                    </div>
                                    <div class="invalid-feedback">
                                            Veuillez ajouter au moins un paramètre.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" id="add-parametre" class="btn btn-outline-secondary">
                                <i class="bi bi-plus-circle"></i> Ajouter paramètre
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                            <!--
                            <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </button>
                            -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="bi bi-info-circle me-1"></i>
                    <h5 class="modal-title" id="alertModalLabel">Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    let parametreIndex = 1;

    document.getElementById('add-parametre').addEventListener('click', function () {
        if (!areAllFieldsFilled()) {
            showAlert("Veuillez sélectionner un paramètre et entrer sa valeur avant d'ajouter un nouveau paramètre.");
            return;
        }

        const container = document.getElementById('parametres-container');

        const selectedValues = Array.from(document.querySelectorAll('[name^="parametres"]')).map(select => select.value);
        const availableOptions = Array.from(document.querySelectorAll('select[name^="parametres"] option')).filter(
            option => !selectedValues.includes(option.value) && option.value !== ""
        );

        // Utiliser un Set pour éliminer les doublons
        const uniqueOptions = Array.from(new Set(availableOptions.map(option => option.value)))
                                   .map(value => availableOptions.find(option => option.value === value));

        if (uniqueOptions.length === 0) {
            showAlert("Vous avez atteint le nombre limite de paramètre disponible.");
            return;
        }

        const newParametre = document.createElement('div');
        newParametre.classList.add('row', 'g-3', 'mb-2', 'parametre-item');
        newParametre.innerHTML = `
            <div class="col-md-6">
                <label for="parametres[${parametreIndex}][id]" class="form-label">Paramètre<span class="text-danger">*</span></label>
                <select name="parametres[${parametreIndex}][id]" class="form-select" required>
                    <option value="">-- Sélectionner un paramètre --</option>
                    ${uniqueOptions.map(option => `<option value="${option.value}">${option.text}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <label for="parametres[${parametreIndex}][valeur]" class="form-label">Valeur<span class="text-danger">*</span></label>
                <input type="number" name="parametres[${parametreIndex}][valeur]" class="form-control" min="0" required placeholder="Valeur du paramètre" oninput="validateInput03()">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-parametre">Supprimer</button>
            </div>
        `;
        container.appendChild(newParametre);
        parametreIndex++;
    });

    document.getElementById('parametres-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-parametre')) {
            e.target.closest('.parametre-item').remove();
        }
    });

    function showAlert(message) {
        document.getElementById('alertModalBody').innerText = message;
        const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        alertModal.show();
    }

    function areAllFieldsFilled() {
        const parametreSelects = document.querySelectorAll('[name^="parametres"][name$="[id]"]');
        const valeurInputs = document.querySelectorAll('[name^="parametres"][name$="[valeur]"]');

        for (let i = 0; i < parametreSelects.length; i++) {
            if (!parametreSelects[i].value || !valeurInputs[i].value) {
                return false;
            }
        }
        return true;
    }
    });

    function validateInput() {
        const input = document.getElementById('prix_modele');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validateInput02() {
        const input = document.getElementById('valeur');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validateInput03() {
        const input = document.getElementById('valeur02');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validateInput04() {
        const input = document.getElementById('valeur03');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

</script>


@endsection
