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
        <div class="col-lg-10">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Beállítások</span>
                    </div>
                    <div class="card-body">
                        <form onkeydown="return event.key !== 'Enter';" id="profile-form" action="{{ route('profile.edit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input maxlength="64" id="nickname" name="nickname" type="text" class="form-control" value="{{ $user->getNickName() }}" placeholder="Becenév">
                                <label for="nickname">Becenév</label>
                            </div>
                            <div class="mb-3">
                                <input accept="image/jpeg, image/png" type="file" class="form-control" id="profile-picture" onchange="loadProfilePicture(event)"/>
                            </div>
                            <div class="mb-3">
                                <img id="profile-picture-preview" alt="{{ $user->getName() }}" src="{{ $user->getProfilePicture() !== null ? route('image', ['imageId' => $user->getProfilePicture()->getId()]) : '' }}" class="img-fluid @if ($user->getProfilePicture() === null) visually-hidden @endif">
                            </div>
                            <button type="button" class="btn btn-primary" id="profile-save-button" onclick="submitProfileForm(event)">Mentés</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
