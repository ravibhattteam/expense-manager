@extends('layouts.app')

@section('content')
<h3>Approval Rule</h3>

<form method="post" action="{{ route('admin.rules.store') }}">
  @csrf
  <div class="mb-3">
    <label>Rule Type</label>
    <select name="rule_type" class="form-control">
      <option value="percentage">Percentage</option>
      <option value="specific">Specific Approver</option>
      <option value="hybrid">Hybrid</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Threshold (percent)</label>
    <input name="threshold" class="form-control" placeholder="e.g. 60">
  </div>

  <div class="mb-3">
    <label>Specific Approver</label>
    <select name="specific_user_id" class="form-control">
      <option value="">-- none --</option>
      @foreach($users as $u)
        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
      @endforeach
    </select>
  </div>

  <button class="btn btn-primary">Save Rule</button>
</form>
@endsection
