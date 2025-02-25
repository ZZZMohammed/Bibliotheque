

@extends('layouts.base')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Edit Profile</h5>

                        <!-- Success message -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Nom</p>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Prénom</p>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control" readonly value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Password (Optional)</p>
                                </div>
                                <div class="col-sm-9">
                                    <input type="password" name="password" class="form-control" placeholder="New password (leave blank to keep current)">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
