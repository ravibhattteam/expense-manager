{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h3>Dashboard</h3>
    <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>My Expenses</h5>
        <p><a href="{{ route('expenses.index') }}" class="btn btn-sm btn-primary">View</a></p>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>Approvals</h5>
        <p><a href="{{ route('approvals.index') }}" class="btn btn-sm btn-primary">Pending</a></p>
      </div>
    </div>
  </div>
</div>
@endsection
