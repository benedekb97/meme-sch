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
                <div class="card-body">
                    <form action="{{ route('profile.edit') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="nickname" name="nickname" type="text" class="form-control" value="{{ $user->getNickName() }}" placeholder="Becenév">
                            <label for="nickname">Becenév</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Mentés</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
