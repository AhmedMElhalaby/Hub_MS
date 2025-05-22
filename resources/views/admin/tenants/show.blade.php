@extends('layouts.admin')

@section('content')
    {{-- The main heading is now part of the Livewire component view for better encapsulation --}}
    <livewire:admin.tenant-detail :tenant="$tenant" />
@endsection
