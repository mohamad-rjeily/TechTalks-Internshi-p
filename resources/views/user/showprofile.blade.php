@extends('layouts.app')

@section('content')
    {{-- We check if the current user (the one viewing the page) is the user whose profile is being viewed --}}
    @if (Auth::check() && Auth::id() == $profileUser->id)
        {{-- CASE 1: It's the user himself, we always display everything --}}
        <h1>My Profile (Private)</h1>
        @include('user.profile_details')

    {{-- We check if the profile visibility is "public" --}}
    @elseif ($profileUser->profile_visibility == 'public')
        {{-- CASE 2: The profile is public, we display it --}}
        <h1>{{ $profileUser->name }}'s Profile</h1>
        @include('user.profile_details')
    
    @else
        {{-- CASE 3: The profile is private and it's not the user himself --}}
        <div class="alert alert-warning">
            This profile is currently set to private.
        </div>
    @endif
@endsection