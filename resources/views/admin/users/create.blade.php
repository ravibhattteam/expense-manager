@extends('layouts.app')

@section('content')
<h3>Create User</h3>

<form method="post" action="{{ route('admin.users.store') }}">
  @csrf
  <div class="mb-3"><label>Name</label><input name="name" class="form-control" required></div>
  <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" required></div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control">
      <option value="employee">Employee</option>
      <option value="manager">Manager</option>
      <option value="admin">Admin</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Manager (optional)</label>
    <select name="manager_id" class="form-control">
      <option value="">-- none --</option>
      @foreach($managers as $m)
        <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
      @endforeach
    </select>
  </div>

  <button class="btn btn-success">Create</button>
</form>
@endsection
