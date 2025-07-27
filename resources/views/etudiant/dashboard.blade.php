@extends('layouts.etudiant')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard Étudiant</div>
                <div class="card-body">
                    <h4>Bienvenue, {{ $user->nom }} {{ $user->prenom }}</h4>
                    <p>Vous êtes connecté en tant qu'étudiant.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
