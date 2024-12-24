@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Modèles d'Abonnement</h1>
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
                    <h5 class="card-title">Informations sur le model</h5>
                    
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
                            <label for="prix_modele" class="form-label">Prix<span class="text-danger">*</span></label>
                            <input 
                                type="number" 
                                name="prix" 
                                id="prix_modele" 
                                class="form-control" 
                                placeholder="Prix du modèle d'abonnement" 
                                required>
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
                                placeholder="Durée du modèle d'abonnement" 
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
                            <label class="form-label">Paramètres modèles<span class="text-danger">*</span></label>
                            <div id="parametres-container">
                                <!-- Champ dynamique pour les paramètres -->
                                <div class="row g-3 mb-2 parametre-item">
                                    <div class="col-md-6">
                                        <label for="parametres[0][id]" class="form-label">Paramètre</label>
                                        <select name="parametres[0][id]" class="form-select" required>
                                            <option value="">-- Sélectionner un paramètre --</option>
                                            @foreach($parametres as $parametre)
                                                <option value="{{ $parametre->id }}">{{ $parametre->nom_parametre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parametres[0][valeur]" class="form-label">Valeur</label>
                                        <input type="number" name="parametres[0][valeur]" class="form-control" min="0" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-parametre">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Veuillez ajouter au moins un paramètre.
                            </div>

                            <button type="button" id="add-parametre" class="btn btn-secondary"><i class="bi bi-plus-circle"></i> Ajouter paramètre</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let parametreIndex = 1;

        // Ajouter un nouveau champ dynamique pour les paramètres
        document.getElementById('add-parametre').addEventListener('click', function () {
            const container = document.getElementById('parametres-container');
            const newParametre = document.createElement('div');
            newParametre.classList.add('row', 'g-3', 'mb-2', 'parametre-item');
            newParametre.innerHTML = `
                <div class="col-md-6">
                    <label for="parametres[${parametreIndex}][id]" class="form-label">Paramètre</label>
                    <select name="parametres[${parametreIndex}][id]" class="form-select" required>
                        <option value="">-- Sélectionner un paramètre --</option>
                        @foreach($parametres as $parametre)
                            <option value="{{ $parametre->id }}">{{ $parametre->nom_parametre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="parametres[${parametreIndex}][valeur]" class="form-label">Valeur</label>
                    <input type="number" name="parametres[${parametreIndex}][valeur]" class="form-control" min="0" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-parametre">Supprimer</button>
                </div>
            `;
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
</script>

@endsection
