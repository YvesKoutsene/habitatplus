@extends('abonné.include.layouts.ap')
@section('content')

<form id="createAdForm" action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h2 class="text-black-50 mb-4">Créer une annonce</h2>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Photos de l'annonce</h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à 6 photos au maximum pour cette annonce.</small>
        </div>
        <div class="card-body d-flex align-items-center">
            <div class="d-flex flex-column align-items-center me-3">
                <label for="photos" class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm" style="width: 100px; height: 100px; cursor: pointer;">
                    <i class="bi bi-image text-muted" style="font-size: 30px;"></i>
                </label>
                <span class="text-muted mt-2" style="font-size: 14px;">Ajouter une photo<span class="text-danger" title="minimun une photo">*</span></span>
            </div>
            <input type="file" class="d-none" id="photos" name="photos[]" multiple accept="image/*">
            <div id="preview" class="d-flex gap-"></div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Catégorie de bien</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="categorySelect" class="form-label text-black">Catégorie<span class="text-danger" title="obligatoire">*</span></label>
                <select class="form-select form-control form-select-sm" id="categorySelect" name="category" required>
                    <option value="" disabled selected>Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="parametersContainer"></div>
        </div>
    </div>
    <!-- Carte pour les détails de l'annonce -->
    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Détails de l'annonce</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="ad_type" class="form-label text-black">Type d'annonce<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <select class="form-select form-control form-select-sm" id="type_annonce" name="type_offre">
                    <option value="" disabled selected>Sélectionnez un type</option>
                    <option value="Location">Location</option>
                    <option value="Vente">Vente</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label text-black">Titre<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="title" name="titre" required placeholder="Titre de votre annonce">
            </div>

            <div class="mb-3">
                <label for="prix" class="form-label text-black">Prix<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        name="prix"
                        id="prix_annonce"
                        class="form-control form-control-sm"
                        placeholder="Prix de votre annonce"
                        min="1" oninput="validateInput01()">
                    <span class="input-group-text">CFA</span>
                </div>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label text-black">Lieu<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="location" name="lieu" placeholder="Lieu où se trouve votre bien">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label text-black">Description<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <textarea class="form-control form-control-sm" id="description" name="description" rows="4" placeholder="Une petite description de votre annonce"></textarea>
                <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" name="action" value="save" class="btn btn-primary px-3 py-2">
                    <i class="bi bi-save me-2"></i> Enregistrer
                </button>
                <button type="submit" name="action" value="publish" class="btn btn-primary btn btn-warning text-white px-3 py-2">
                    <i class="bi bi-megaphone me-2"></i> Publier
                </button>
            </div>
        </div>
    </div>
</form>

<script>

    const photosInput = document.getElementById('photos');
    const previewContainer = document.getElementById('preview');

    const MAX_PHOTOS = 6;

    photosInput.addEventListener('change', function(event) {
        const files = event.target.files;

        if (files.length + previewContainer.childElementCount > MAX_PHOTOS) {
            alert('Vous pouvez téléverser jusqu’à 6 photos au maximum.');
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (previewContainer.childElementCount >= MAX_PHOTOS) break;

            const reader = new FileReader();

            reader.onload = function(e) {
                const photoWrapper = document.createElement('div');
                photoWrapper.className = 'photo-wrapper position-relative';
                photoWrapper.style.width = '100px';
                photoWrapper.style.height = '100px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';

                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
                removeBtn.innerHTML = '<i class="bi bi-x-circle"></i>';
                removeBtn.style.padding = '4px';

                removeBtn.addEventListener('click', function() {
                    photoWrapper.remove();
                });

                photoWrapper.appendChild(img);
                photoWrapper.appendChild(removeBtn);
                previewContainer.appendChild(photoWrapper);
            };

            reader.readAsDataURL(file);
        }
    });

    function validateInput01() {
        const input = document.getElementById('prix_annonce');
        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value.length > 8) {
            input.value = input.value.substring(0, 8);
        }
    }

    document.getElementById('createAdForm').addEventListener('submit', function(event) {
        const action = event.submitter.value;
        const requiredFields = ['categorySelect', 'title'];

        if (action === 'publish') {
            requiredFields.push('prix_annonce', 'location', 'description', 'type_annonce');

            const parameterFields = document.querySelectorAll('#parametersContainer input');
            parameterFields.forEach(field => {
                requiredFields.push(field.id);
            });
        }

        let isValid = true;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires pour publier.');
        }
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categories = @json($categories);

        var categorySelect = document.getElementById('categorySelect');
        var parametersContainer = document.getElementById('parametersContainer');

        function validateInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value.length > 8) {
                input.value = input.value.substring(0, 8);
            }
        }

        function updateParameters(categoryId) {
            parametersContainer.innerHTML = '';

            var selectedCategory = categories.find(function (cat) {
                return cat.id === parseInt(categoryId);
            });

            if (!selectedCategory) {
                parametersContainer.innerHTML = '<p>Aucun paramètre disponible pour cette catégorie.</p>';
                return;
            }

            selectedCategory.associations.forEach(function (assoc) {
                var parameterId = assoc.id_parametre;

                var inputGroup = document.createElement('div');
                inputGroup.classList.add('mb-3', 'row');

                var labelCol = document.createElement('div');
                labelCol.classList.add('col-md-7');

                var label = document.createElement('label');
                label.textContent = assoc.parametre.nom_parametre;
                label.classList.add('form-label');

                var requiredIndicator = document.createElement('span');
                requiredIndicator.textContent = '*';
                requiredIndicator.classList.add('text-danger');
                requiredIndicator.title = "obligatoire pour publier votre annonce";

                labelCol.appendChild(label);
                labelCol.appendChild(requiredIndicator);

                var inputCol = document.createElement('div');
                inputCol.classList.add('col-md-5');

                var input = document.createElement('input');
                input.type = 'text';
                input.name = 'parameters[' + parameterId + ']';
                input.id = 'param_' + parameterId;
                input.classList.add('form-control');
                input.placeholder = "Entrez une valeur";
                /*input.required = true;*/
                input.required = false;

                input.addEventListener('input', function () {
                    validateInput(input);
                });

                inputCol.appendChild(input);
                inputGroup.appendChild(labelCol);
                inputGroup.appendChild(inputCol);

                parametersContainer.appendChild(inputGroup);
            });
        }

        categorySelect.addEventListener('change', function () {
            var selectedCategoryId = this.value;
            updateParameters(selectedCategoryId);
        });
    });
</script>

<style>
    .photo-wrapper {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .photo-wrapper:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .photo-wrapper img {
        display: block;
    }

    .photo-wrapper button {
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .photo-wrapper button:hover {
        background-color: rgba(255, 0, 0, 0.8);
        color: white;
    }

    .upload-icon {
        transition: background-color 0.2s ease-in-out;
    }

    .upload-icon:hover {
        background-color: #f8f9fa;
    }

    .is-invalid {
        border-color: red;
        background-color: #f8d7da;
    }

</style>

@endsection
