@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container py-4 ">
    {{-- Reports Livewire Component --}}
    @livewire('reports')
</div>
@endsection