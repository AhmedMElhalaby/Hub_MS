@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8">
        {{-- The heading "Create Admin User" is now part of the Livewire component for consistency --}}
        <livewire:admin.admin-user-form />
    </div>
@endsection
