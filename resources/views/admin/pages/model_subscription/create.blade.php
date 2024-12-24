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
                    
                    <form action="{{ route('model_subscription.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Nom du modèle d'abonnement -->
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

                        <div class="mb-4">
                            <label for="prix_modele" class="form-label">Prix (FCFA)<span class="text-danger">*</span></label>
                            <input 
                                type="number" 
                                name="prix" 
                                id="prix_modele" 
                                class="form-control" 
                                placeholder="Prix du modèle d'abonnement" 
                                required min="0" oninput="validateInput()">
                            <div class="invalid-feedback">
                                Veuillez fournir un prix pour le modèle.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="duree_modele" class="form-label">Duree<span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="duree" 
                                id="duree_modele" 
                                class="form-control" 
                                placeholder="Durée du modèle d'abonnement (Ex : 3 Mois)" 
                                required>
                            <div class="invalid-feedback">
                                Veuillez fournir une durée pour le modèle.
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
                                        <div class="invalid-feedback">
                                            Veuillez ajouter au moins un paramètre.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parametres[0][valeur]" class="form-label">Valeur<span class="text-danger">*</span></label>
                                        <input type="number" name="parametres[0][valeur]" class="form-control" min="0" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-parametre">Supprimer</button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-parametre" class="btn btn-outline-secondary"><i class="bi bi-plus-circle"></i> Ajouter paramètre</button>
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!--
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let parametreIndex = 1;

    document.getElementById('add-parametre').addEventListener('click', function () {
        const container = document.getElementById('parametres-container');

        const selectedValues = Array.from(document.querySelectorAll('[name^="parametres"]')).map(select => select.value);
        const availableOptions = Array.from(document.querySelectorAll('select[name^="parametres"] option')).filter(
            option => !selectedValues.includes(option.value) && option.value !== ""
        );

        if (availableOptions.length === 0) {
            alert("Tous les paramètres disponibles ont déjà été sélectionnés.");
            return;
        }

        const newParametre = document.createElement('div');
        newParametre.classList.add('row', 'g-3', 'mb-2', 'parametre-item');
        newParametre.innerHTML = `
            <div class="col-md-6">
                <label for="parametres[${parametreIndex}][id]" class="form-label">Paramètre<span class="text-danger">*</span></label>
                <select name="parametres[${parametreIndex}][id]" class="form-select" required>
                    <option value="">-- Sélectionner un paramètre --</option>
                    ${availableOptions.map(option => `<option value="${option.value}">${option.text}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <label for="parametres[${parametreIndex}][valeur]" class="form-label">Valeur<span class="text-danger">*</span></label>
                <input type="number" name="parametres[${parametreIndex}][valeur]" class="form-control" min="0" required>
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
    });

    function validateInput() {
        const input = document.getElementById('prix_modele');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

</script>
-->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let parametreIndex = 1;

        document.getElementById('add-parametre').addEventListener('click', function () {
            const container = document.getElementById('parametres-container');
            const newParametre = document.createElement('div');
            newParametre.classList.add('row', 'g-3', 'mb-2', 'parametre-item');
            newParametre.innerHTML = 
                <div class="col-md-6">
                    <label for="parametres[${parametreIndex}][id]" class="form-label">Paramètre<span class="text-danger">*</span></label>
                    <select name="parametres[${parametreIndex}][id]" class="form-select" required>
                        <option value="">-- Sélectionner un paramètre --</option>
                        @foreach($parametres as $parametre)
                            <option value="{{ $parametre->id }}">{{ $parametre->nom_parametre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="parametres[${parametreIndex}][valeur]" class="form-label">Valeur<span class="text-danger">*</span></label>
                    <input type="number" name="parametres[${parametreIndex}][valeur]" class="form-control" min="0" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-parametre">Supprimer</button>
                </div>
            ;
            container.appendChild(newParametre);
            parametreIndex++;
        });

        // Supprimer un champ dynamique
        document.getElementById('parametres-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-parametre')) {
                e.target.closest('.parametre-item').remove();
            }
        });
    });

    // Validation des champs prix
    function validateInput() {
        const input = document.getElementById('prix_modele');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

</script>

@endsection
