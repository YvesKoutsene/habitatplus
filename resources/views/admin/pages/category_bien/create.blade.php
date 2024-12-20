@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Ajouter une Catégorie</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category_bien.index') }}">Catégories Bien</a></li>
            <li class="breadcrumb-item active">Ajouter Catégorie</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations sur la Catégorie</h5>
                    
                    <form action="{{ route('category_bien.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Nom de la Catégorie -->
                        <div class="mb-4">
                            <label for="nom_categorie" class="form-label">Nom de la Catégorie <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="nom_categorie" 
                                id="nom_categorie" 
                                class="form-control" 
                                placeholder="Entrez le nom de la catégorie" 
                                required>
                            <div class="invalid-feedback">
                                Veuillez fournir un nom pour la catégorie.
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea 
                                name="description" 
                                id="description" 
                                class="form-control" 
                                rows="3" 
                                maxlength="100" 
                                placeholder="Ajoutez une description (100 caractères maximum)" 
                                required></textarea>
                            <div class="invalid-feedback">
                                Veuillez fournir une description valide.
                            </div>
                            <small class="text-muted">100 caractères maximum.</small>
                        </div>

                        <!-- Paramètres de Catégorie -->
                        <div class="mb-4">
                            <label class="form-label">Paramètres Associés <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach($parametres as $parametre)
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <div class="form-check">
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
                            </div>
                            <div class="invalid-feedback">
                                Veuillez sélectionner au moins un paramètre.
                            </div>
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Enregistrer
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

@endsection
