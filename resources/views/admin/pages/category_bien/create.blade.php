@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Catégorie</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('category_bien.index') }}">Catégories Bien</a></li>
            <li class="breadcrumb-item active">Créer Catégorie</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations sur la catégorie</h5>

                    <form action="{{ route('category_bien.store') }}" method="POST" class="needs-validation">
                        @csrf
                        <div class="mb-4">
                            <label for="nom_categorie" class="form-label">Nom<span class="text-danger" title="Obligatoire">*</span></label>
                            <input
                                type="text"
                                name="titre"
                                id="nom_categorie"
                                class="form-control"
                                placeholder="Nom de la catégorie de bien"
                                required>
                            <div class="invalid-feedback">
                                Veuillez fournir un nom pour la catégorie.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description<span class="text-danger" title="Obligatoire">*</span></label>
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

                        <div class="mb-4">
                            <label class="form-label">Paramètres catégories<span class="text-danger" title="Obligatoire">*</span></label>
                            <div class="row" id="parametres_existants">
                                @foreach($parametres as $parametre)
                                <div class="col-md-6 col-sm-10 mb-2">
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="parametres[]"
                                            id="parametre{{ $parametre->id }}"
                                            value="{{ $parametre->id }}">
                                        <label class="form-check-label" for="parametre{{ $parametre->id }}">
                                            {{ $parametre->nom_parametre }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                                <div id="nouveaux_parametres" class="row"></div>
                            </div>
                            <div class="invalid-feedback">
                                Veuillez sélectionner au moins un paramètre.
                            </div>
                            <label class="form-label">
                                <a href="#" id="ajouter_parametre" style="text-decoration: underline;" onclick="ouvrirModaleAjout()">Ajouter autres paramètres</a>
                            </label>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </button>
                        </div>
                    </form>

                    <div class="modal fade" id="modalParametre" tabindex="-1" aria-labelledby="modalParametreLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalParametreLabel">Ajouter Paramètre</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="nom_parametre" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                                    <input type="text" id="nouveau_parametre" class="form-control" required placeholder="Nom du paramètre">
                                    <div id="message_erreur" class="text-danger mt-2" style="display: none;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                    <button type="button" class="btn btn-primary" onclick="ajouterNouveauParametre()"><i class="bi bi-check-circle"></i> Ajouter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modale pour modifier un paramètre -->
                    <div class="modal fade" id="modalModifierParametre" tabindex="-1" aria-labelledby="modalModifierParametreLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalModifierParametreLabel">Modifier Paramètre</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="nom_parametre" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                                    <input type="text" id="parametre_modifie" class="form-control" placeholder="Nom du paramètre" required>
                                    <div id="message_erreur02" class="text-danger mt-2" style="display: none;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                    <button type="button" class="btn btn-primary" onclick="validerModification()"><i class="bi bi-check-circle"></i> Modifier</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let parametreActuel;

    function ouvrirModaleAjout() {
        const modal = new bootstrap.Modal(document.getElementById('modalParametre'));
        modal.show();
    }

    function ajouterNouveauParametre() {
        const input = document.getElementById('nouveau_parametre');
        const nomParametre = input.value.trim();
        const messageErreur = document.getElementById('message_erreur');

        const parametresExistants = Array.from(document.querySelectorAll('#parametres_existants .form-check-label')).map(label => label.innerText);
        const nouveauxParametres = Array.from(document.querySelectorAll('#nouveaux_parametres .form-check-label')).map(label => label.innerText);

        if (parametresExistants.includes(nomParametre) || nouveauxParametres.includes(nomParametre)) {
            messageErreur.innerText = "Ce paramètre existe déjà. Veuillez en choisir un autre.";
            messageErreur.style.display = "block";
            return;
        } else {
            messageErreur.style.display = "none";
        }

        if (nomParametre) {
            const container = document.getElementById('nouveaux_parametres');
            const col = document.createElement('div');
            col.className = 'col-md-6 col-sm-10 mb-2';
            const formCheck = document.createElement('div');
            formCheck.className = 'form-check form-switch';
            const checkbox = document.createElement('input');
            checkbox.className = 'form-check-input';
            checkbox.type = 'checkbox';
            checkbox.name = 'autres_parametres[]';
            checkbox.value = nomParametre;
            checkbox.checked = true;
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.innerText = nomParametre;

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mt-2';

            const modifierButton = document.createElement('button');
            modifierButton.className = 'btn btn-warning btn-sm me-2';
            modifierButton.innerHTML = '<i class="bi bi-pencil-square"></i>';
            modifierButton.title = "Modifier";
            modifierButton.onclick = function() {
                ouvrirModaleModification(nomParametre, label, checkbox);
            };

            const supprimerButton = document.createElement('button');
            supprimerButton.className = 'btn btn-danger btn-sm';
            supprimerButton.innerHTML = '<i class="bi bi-trash"></i>';
            supprimerButton.title = "Supprimer";
            supprimerButton.onclick = function() {
                container.removeChild(col);
            };

            formCheck.appendChild(checkbox);
            formCheck.appendChild(label);
            //buttonContainer.appendChild(modifierButton);
            buttonContainer.appendChild(supprimerButton);
            col.appendChild(formCheck);
            col.appendChild(buttonContainer);
            container.appendChild(col);

            const modal = bootstrap.Modal.getInstance(document.getElementById('modalParametre'));
            modal.hide();

            input.value = '';
        } else {
            messageErreur.innerText = "Veuillez entrer un nom de paramètre valide.";
            messageErreur.style.display = "block";
        }
    }

    function ouvrirModaleModification(nomParametre, label, checkbox) {
        parametreActuel = { label, checkbox };
        const input = document.getElementById('parametre_modifie');
        input.value = nomParametre;
        const modal = new bootstrap.Modal(document.getElementById('modalModifierParametre'));
        modal.show();
    }

    function validerModification() {
        const input = document.getElementById('parametre_modifie');
        const nouveauNom = input.value.trim();
        const messageErreur = document.getElementById('message_erreur02');

        if (nouveauNom === parametreActuel.label.innerText) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalModifierParametre'));
            modal.hide();
            return;
        }

        const parametresExistants = Array.from(document.querySelectorAll('#parametres_existants .form-check-label')).map(label => label.innerText);
        const nouveauxParametres = Array.from(document.querySelectorAll('#nouveaux_parametres .form-check-label')).map(label => label.innerText);

        if (parametresExistants.includes(nouveauNom) || nouveauxParametres.includes(nouveauNom)) {
            messageErreur.innerText = "Ce paramètre existe déjà. Veuillez en choisir un autre.";
            messageErreur.style.display = "block";
            return;
        } else {
            messageErreur.style.display = "none";
        }

        if (nouveauNom) {
            parametreActuel.label.innerText = nouveauNom;
            parametreActuel.checkbox.value = nouveauNom;
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalModifierParametre'));
            modal.hide();
        } else {
            messageErreur.innerText = "Veuillez entrer un nom de paramètre valide.";
            messageErreur.style.display = "block";
        }
    }

</script>

@endsection
