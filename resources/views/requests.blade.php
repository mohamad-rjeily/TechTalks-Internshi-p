@extends('layouts.app')

@section('title', 'Requests')

@section('content')
<div class="container py-4 ">
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition
             class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" aria-label="Close" @click="show = false"></button>
        </div>
    @endif
    {{-- Requests Livewire Component --}}
    <livewire:requests />
</div>
@endsection
