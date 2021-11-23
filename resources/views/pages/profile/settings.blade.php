@extends('layouts.main')

@section('title', 'Profilbeállítások')

@section('content')
    <div class="d-flex row mt-3 justify-content-center">
        <div class="col-2">
            <div class="list-group">
                <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">Posztok</a>
                <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action active">Beállítások</a>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <p class="card-title">Beállítások</p>
                </div>
            </div>
        </div>
    </div>
@endsection
