@extends('layouts.app')

@section('content')
<h3>Pending Approvals</h3>
<table class="table table-bordered">
  <thead><tr><th>#</th><th>User</th><th>Amount</th><th>Category</th><th>Actions</th></tr></thead>
  <tbody>
    @foreach($pending as $e)
      <tr>
        <td>{{ $e->id }}</td>
        <td>{{ $e->user->name }}</td>
        <td>{{ $e->amount_in_company_currency }} {{ $e->company->default_currency }}</td>
        <td>{{ $e->category }}</td>
        <td style="width:360px;">
          <form method="post" action="{{ route('approvals.approve', $e) }}" style="display:inline-block; width:48%;">
            @csrf
            <input name="comments" class="form-control mb-1" placeholder="Comments (optional)">
            <button class="btn btn-sm btn-primary w-100">Approve</button>
          </form>

          <form method="post" action="{{ route('approvals.reject', $e) }}" style="display:inline-block; width:48%; margin-left:4%;">
            @csrf
            <input name="comments" class="form-control mb-1" placeholder="Comments (optional)">
            <button class="btn btn-sm btn-danger w-100">Reject</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
