@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Expenses</h3>
        <a href="{{ route('expenses.create') }}" class="btn btn-success">Submit Expense</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Amount</th>
                <th>Company Amount</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $e)
                <tr>
                    <td>{{ $e->id }}</td>
                    <td>{{ $e->user->name }}</td>
                    <td>{{ $e->amount }} {{ $e->currency }}</td>
                    <td>{{ $e->amount_in_company_currency }} {{ $e->company->default_currency }}</td>
                    <td>{{ $e->category }}</td>
                    <td>{{ ucfirst($e->status) }}</td>
                    {{-- <td>{{ $e->expense_date ? $e->expense_date->format('Y-m-d') : '-' }}</td> --}}
                    <td>
                        {{ $e->expense_date ? \Carbon\Carbon::parse($e->expense_date)->format('Y-m-d') : '-' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
