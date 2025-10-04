@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="card-title text-center mb-3">Expense Management</h2>
        <p class="text-center text-muted">Login to manage or approve expenses</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary">Login</button>
          </div>
        </form>

        <div class="mt-3 text-center">
          <a href="{{ route('register') }}">Create an account</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
