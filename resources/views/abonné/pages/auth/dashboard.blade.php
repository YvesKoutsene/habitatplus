@extends('abonné.include.layouts.apps')

@section('content')

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">
        @include('abonné.pages.auth.profile')
    </div>
    <div class="tab-pane fade" id="annonces" role="tabpanel" aria-labelledby="annonces-tab">
    </div>
    <div class="tab-pane fade" id="favoris" role="tabpanel" aria-labelledby="favoris-tab">
    </div>
    <div class="tab-pane fade" id="alertes" role="tabpanel" aria-labelledby="alertes-tab">
    </div>
</div>

@endsection
