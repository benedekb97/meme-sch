@extends('layouts.main')

@section('title', 'Profilom')

@section('content')
    <div class="d-flex row mt-3 justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    {{ $user->getName() }}
                </div>
                <div class="card-body">
                    Profil... majd később :D
                </div>
            </div>
        </div>
    </div>
@endsection
