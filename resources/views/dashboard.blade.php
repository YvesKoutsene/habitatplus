@extends('admin.include.layouts.app')
@section('content')

    <div class="pagetitle">
        <h1>Tableau de bord</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active">Tableau de bord</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

@endsection
