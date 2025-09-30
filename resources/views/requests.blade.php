@extends('layouts.app')

@section('title', 'Requests')

@section('content')
<div class="container py-4 ">
    {{-- Requests Livewire Component --}}
    @livewire('requests')
</div>
@endsection
