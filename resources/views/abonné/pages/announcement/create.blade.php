@extends('abonné.include.layouts.ap')

@section('content')

<form id="createAdForm" action="" method="POST" enctype="multipart/form-data">
    @csrf
    <h2 class="text-black-50 mb-4">Formulaire d'annonce</h2>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Téléverser des photos<span class="text-danger" title="obligatoire">*</span></h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à 5 photos maximun pour cette annonce.</small>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="file" class="form-control form-control-sm" id="photos" name="photos[]" multiple accept="image/*">
            </div>
            <div id="preview" class="mt-3"></div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Catégorie de bien<span class="text-danger" title="obligatoire">*</span></h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="category" class="form-label text-black">Catégorie de bien<span class="text-danger" title="obligatoire">*</span></label>
                <select class="form-select form-control form-select-sm" id="category" name="category" required>
                    <option value="" disabled selected>Sélectionnez une catégorie</option>
                    <option value="Appartement">Appartement</option>
                    <option value="Maison">Maison</option>
                    <option value="Terrain">Terrain</option>
                    <option value="Bureau">Bureau</option>
                </select>
            </div>
            <!-- Ajoutez ici les paramètres spécifiques à la catégorie si nécessaire -->
        </div>
    </div>

    <!-- Carte pour les détails de l'annonce -->
    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Détails de l'annonce<span class="text-danger" title="obligatoire">*</span></h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="ad_type" class="form-label text-black">Type d'annonce<span class="text-danger" title="obligatoire">*</span></label>
                <select class="form-select form-control form-select-sm" id="ad_type" name="ad_type" required>
                    <option value="" disabled selected>Sélectionnez un type</option>
                    <option value="Location">Location</option>
                    <option value="Vente">Vente</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label text-black">Titre<span class="text-danger" title="obligatoire">*</span></label>
                <input type="text" class="form-control form-control-sm" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label text-black">Prix (XOF)<span class="text-danger" title="obligatoire">*</span></label>
                <input type="number" class="form-control form-control-sm" id="price" name="price" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label text-black">Lieu<span class="text-danger" title="obligatoire">*</span></label>
                <input type="text" class="form-control form-control-sm" id="location" name="location" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label text-black">Description<span class="text-danger" title="obligatoire">*</span></label>
                <textarea class="form-control form-control-sm" id="description" name="description" rows="4" required></textarea>
                <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-save me-2"></i> Enregistrer
                </button>
                <button type="submit" class="btn btn-danger btn-publish rounded px-3 py-2">
                    <i class="bi bi-megaphone me-2"></i> Publier
                </button>
            </div>
        </div>
    </div>

</form>

<script>
    document.getElementById('photos').addEventListener('change', function(event) {
        const preview = document.getElementById('preview');
        preview.innerHTML = ''; // Réinitialiser l'aperçu
        const files = event.target.files;

        for (let i = 0; i < files.length && i < 5; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail me-2';
                img.style.width = '100px';
                img.style.height = '100px';
                preview.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
