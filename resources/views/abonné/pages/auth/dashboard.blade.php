@extends('abonné.include.layouts.apps')

@section('content')

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">
        @include('abonné.pages.auth.profile')
    </div>
    <div class="tab-pane fade" id="annonces" role="tabpanel" aria-labelledby="annonces-tab">
        <!-- Contenu des annonces -->
    </div>
    <div class="tab-pane fade" id="favoris" role="tabpanel" aria-labelledby="favoris-tab">
        <!-- Contenu des favoris -->
    </div>
    <div class="tab-pane fade" id="alertes" role="tabpanel" aria-labelledby="alertes-tab">
        <!-- Contenu des alertes -->
    </div>
</div>

@endsection
