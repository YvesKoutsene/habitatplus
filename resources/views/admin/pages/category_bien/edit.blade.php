@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Catégorie</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('category_bien.index') }}">Catégories Bien</a></li>
            <li class="breadcrumb-item active">Modifier Catégorie</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations sur la catégorie</h5>

                    <form action="{{ route('category_bien.update', $categorie->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Nom de la Catégorie -->
                        <div class="mb-4">
                            <label for="nom_categorie" class="form-label">Nom<span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="titre" 
                                id="nom_categorie" 
                                class="form-control" 
                                placeholder="Nom de la catégorie de bien" 
                                value="{{ $categorie->titre }}" 
                                required>
                            <div class="invalid-feedback">
                                Veuillez fournir un nom pour la catégorie.
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
                                required>{{ $categorie->description }}</textarea>
                            <div class="invalid-feedback">
                                Veuillez fournir une description valide.
                            </div>
                            <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                        </div>

                        <!-- Paramètres de Catégorie -->
                        <div class="mb-4">
                            <label class="form-label">Paramètres catégories<span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach($parametres as $parametre)
                                    <div class="col-md-6 col-sm-10 mb-2">
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox"
                                                name="parametres[]" 
                                                id="parametre{{ $parametre->id }}" 
                                                value="{{ $parametre->id }}"
                                                {{ in_array($parametre->id, $categorie->associations->pluck('id_parametre')->toArray()) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="parametre{{ $parametre->id }}">
                                                {{ $parametre->nom_parametre }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback">
                                Veuillez sélectionner au moins un paramètre.
                            </div>
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Mettre à jour
                            </button>
                            <a href="{{ route('category_bien.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
