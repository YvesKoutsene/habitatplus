@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Ajouter une Catégorie de Bien</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category_bien.index') }}">Catégories</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Créer une nouvelle Catégorie</h5>

                    <form action="{{ route('category_bien.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nom_categorie" class="form-label">Nom de la Catégorie</label>
                            <input type="text" name="nom_categorie" id="nom_categorie" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paramètres de Catégorie</label>
                            <div class="form-check">
                                @foreach($parametres as $parametre)
                                    <input type="checkbox" name="parametres[]" id="parametre{{ $parametre->id }}" value="{{ $parametre->id }}" class="form-check-input">
                                    <label for="parametre{{ $parametre->id }}" class="form-check-label">
                                        {{ $parametre->nom_parametre }}
                                    </label><br>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('category_bien.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
