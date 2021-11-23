@extends('layouts.main')

@section('title', 'Profilbeállítások')

@section('content')
    <div class="d-flex row mt-3 justify-content-center">
        <div class="col-lg-2 mb-3">
            <div class="list-group">
                <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">Posztok</a>
                <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action active">Beállítások</a>
            </div>
        </div>
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <p class="card-title">Beállítások</p>
                </div>
{{--                <form action="{{ route('profile.edit') }}" method="POST">--}}
{{----}}
{{--                </form>--}}
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection
