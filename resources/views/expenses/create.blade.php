@extends('layouts.app')

@section('content')
<h3>Submit Expense</h3>
<form action="{{ route('expenses.store') }}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
    <label>Amount</label>
    <input name="amount" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Currency</label>
    <input name="currency" class="form-control" value="{{ auth()->user()->company->default_currency ?? 'INR' }}" required>
  </div>

  <div class="mb-3">
    <label>Category</label>
    <input name="category" class="form-control">
  </div>

  <div class="mb-3">
    <label>Expense Date</label>
    <input type="date" name="expense_date" class="form-control">
  </div>

  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control"></textarea>
  </div>

  <div class="mb-3">
    <label>Receipt (optional)</label>
    <input type="file" name="receipt" class="form-control">
  </div>

  <button class="btn btn-primary">Submit</button>
</form>
@endsection
