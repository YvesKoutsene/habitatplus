@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Catégorie</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category_bien.index') }}">Catégories Bien</a></li>
            <li class="breadcrumb-item active">Ajouter Categorie</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Information sur la categorie</h5>

                    <form action="{{ route('category_bien.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nom_categorie" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                            <input type="text" name="nom_categorie" id="nom_categorie" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="col-sm-12">
                                <textarea class="form-control" style="height: 100px"name="description" id="description" required></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="col-sm-2 col-form-label">Paramètres de Catégorie<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="col-sm-10">
                                <div class="form-check form-switch">
                                @foreach($parametres as $parametre)
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="parametres[]" id="parametre{{ $parametre->id }}" value="{{ $parametre->id }}">
                                    <label class="form-check-label" for="flexSwitchCheckDefault parametre{{ $parametre->id }}">{{ $parametre->nom_parametre }}</label> <br>
                                @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Créer</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
