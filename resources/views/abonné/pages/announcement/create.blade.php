@extends('abonné.include.layouts.ap')
@section('content')

<form id="createAdForm" action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h2 class="text-black-50 mb-4">Créer votre annonce</h2>
    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Photos de l'annonce</h5>
            <small class="text-muted">Vous pouvez ajouter jusqu'à 7 photos au maximum pour cette annonce, une principale et six annexes.</small>
        </div>
        <div class="card-body d-flex flex-wrap gap-3">
            @php
            $maxPhotos = 7;
            @endphp
            @for ($i = 0; $i < $maxPhotos; $i++)
            <div class="d-flex flex-column align-items-center">
                <label for="photo_{{ $i }}" class="upload-icon bg-light d-flex align-items-center justify-content-center border rounded shadow-sm position-relative" style="width: 100px; height: 100px; cursor: pointer;">
                    <img id="preview_photo_{{ $i }}" alt="" class="img-thumbnail d-none" style="width: 100%; height: 100%; object-fit: cover;">
                    <i class="bi bi-image text-muted preview-icon" style="font-size: 30px;"></i>
                    <button type="button" id="remove_photo_{{ $i }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none" style="padding: 4px;">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </label>
                <input type="file" class="d-none" id="photo_{{ $i }}" name="photos[]" accept="image/*">
                <span class="text-muted mt-2" style="font-size: 14px;">Photo {{ $i === 0 ? 'principale' : 'annexe ' . $i }}</span>
            </div>
            @endfor
        </div>
    </div>
    <div class="card shadow-lg border-0 rounded-lg mb-4">
        <div class="card-header text-black">
            <h5>Détails de l'annonce</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="title" class="form-label text-black">Titre<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <input type="text" class="form-control form-control-sm" id="title" name="titre" required placeholder="Titre de votre annonce">
            </div>
            <div class="mb-3">
                <label for="ad_type" class="form-label text-black">Type d'annonce<span class="text-danger" title="obligatoire pour publier votre annonce">*</span></label>
                <select class="form-select form-control form-select-sm" id="type_annonce" name="type_offre">
                    <option value="" disabled selected>Sélectionnez un type</option>
                    <option value="Location">Location</option>
                    <option value="Vente">Vente</option>
                </select>
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
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header yes">
                    <h5 class="modal-title" id="validationModalLabel">Erreur de Validation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-black" id="validationMessage">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function showModal(message) {
        document.getElementById('validationMessage').innerText = message;
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        modal.show();
    }

    function setupPhotoPreview(inputId, previewId, removeButtonId, index) {
        const inputElement = document.getElementById(inputId);
        const previewElement = document.getElementById(previewId);
        const previewIcon = previewElement.nextElementSibling;
        const removeButton = document.getElementById(removeButtonId);

        inputElement.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (index > 0 && !document.getElementById(`photo_${index - 1}`).value) {
                showModal("Vous devez d'abord ajouter la photo précédente avant de pouvoir ajouter celle-ci.");
                inputElement.value = '';
                return;
            }

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('d-none');
                    previewIcon.classList.add('d-none');
                    removeButton.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function () {
            for (let i = index + 1; i < {{ $maxPhotos }}; i++) {
                if (document.getElementById(`photo_${i}`).value) {
                    showModal("Vous devez d'abord supprimer les photos suivantes avant de pouvoir retirer celle-ci.");
                    return;
                }
            }

            inputElement.value = '';
            previewElement.src = '';
            previewElement.classList.add('d-none');
            previewIcon.classList.remove('d-none');
            removeButton.classList.add('d-none');
        });
    }

    const maxPhotos = {{ $maxPhotos }};
    for (let i = 0; i < maxPhotos; i++) {
        setupPhotoPreview(`photo_${i}`, `preview_photo_${i}`, `remove_photo_${i}`, i);
    }
</script>

<script>
    function validateInput01() {
        const input = document.getElementById('prix_annonce');
        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value.length > 10) {
            input.value = input.value.substring(0, 10);
        }
    }

    document.getElementById('createAdForm').addEventListener('submit', function(event) {
        const action = event.submitter.value;
        const requiredFields = ['categorySelect', 'title'];
        let isValid = true;

        if (action === 'publish') {
            requiredFields.push('prix_annonce', 'location', 'description', 'type_annonce');

            const parameterFields = document.querySelectorAll('#parametersContainer input');
            parameterFields.forEach(field => {
                requiredFields.push(field.id);
            });
        }
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
            const message = 'Veuillez remplir tous les champs obligatoires pour publier.';
            document.getElementById('validationMessage').textContent = message;
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            validationModal.show();
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

            if (input.value.length > 10) {
                input.value = input.value.substring(0, 10);
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

                input.name = 'parameters[' + assoc.id + ']';

                input.id = 'param_' + parameterId;
                input.classList.add('form-control');
                input.placeholder = "Entrez une valeur";
                input.required = true;

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

    .yes{
        background-color: #007bff;
    }

</style>

@endsection
