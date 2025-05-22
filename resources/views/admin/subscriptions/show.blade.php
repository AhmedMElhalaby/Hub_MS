@extends('layouts.admin')

@section('content')
    {{-- The main heading is now part of the Livewire component view for better encapsulation --}}
    <livewire:admin.subscription-detail :subscription="$subscription" />
@endsection
