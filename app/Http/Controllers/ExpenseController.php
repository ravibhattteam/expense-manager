<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'employee') {
            $expenses = $user->expenses()->latest()->get();
        } else {
            $expenses = Expense::where('company_id', $user->company_id)->latest()->get();
        }
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    // public function store(Request $r, CurrencyService $currencyService)
    // {
    //     $r->validate([
    //         'amount' => 'required|numeric',
    //         'currency' => 'required|string|max:3',
    //         'category' => 'nullable|string|max:255',
    //         'expense_date' => 'nullable|date',
    //         'receipt' => 'nullable|file|max:4096',
    //         'description' => 'nullable|string',
    //     ]);

    //     $user = $r->user();
    //     $company = $user->company;

    //     $receiptPath = null;
    //     if ($r->hasFile('receipt')) {
    //         $receiptPath = $r->file('receipt')->store('receipts');
    //     }

    //     $amountInCompany = $currencyService->convertToCompanyCurrency(
    //         $r->amount,
    //         strtoupper($r->currency),
    //         strtoupper($company->default_currency ?? 'USD')
    //     );

    //     $expense = Expense::create([
    //         'company_id' => $company->id,
    //         'user_id' => $user->id,
    //         'amount' => $r->amount,
    //         'currency' => strtoupper($r->currency),
    //         'amount_in_company_currency' => $amountInCompany ?? $r->amount,
    //         'category' => $r->category,
    //         'description' => $r->description,
    //         'expense_date' => $r->expense_date,
    //         'receipt_path' => $receiptPath,
    //     ]);

    //     // Create approval step(s)
    //     $sequence = 1;
    //     if ($user->manager_id && $user->is_manager_approver) {
    //         ExpenseApproval::create([
    //             'expense_id' => $expense->id,
    //             'approver_id' => $user->manager_id,
    //             'sequence' => $sequence
    //         ]);
    //         $expense->current_approver_id = $user->manager_id;
    //         $expense->save();
    //         $sequence++;
    //     }

    //     // Add admin as next approver if no finance
    //     $financeUser = User::where('role', 'manager')->where('email', 'like', '%finance%')->first();
    //     if ($financeUser) {
    //         ExpenseApproval::create(['expense_id' => $expense->id, 'approver_id' => $financeUser->id, 'sequence' => $sequence]);
    //     } else {
    //         $admin = User::where('role', 'admin')->first();
    //         if ($admin) {
    //             ExpenseApproval::create(['expense_id' => $expense->id, 'approver_id' => $admin->id, 'sequence' => $sequence]);
    //             if (! $expense->current_approver_id) {
    //                 $expense->current_approver_id = $admin->id;
    //                 $expense->save();
    //             }
    //         }
    //     }

    //     return redirect()->route('expenses.index')->with('success', 'Expense submitted');
    // }


    public function store(Request $r, CurrencyService $currencyService)
{
    $r->validate([
        'amount' => 'required|numeric',
        'currency' => 'required|string|max:3',
        'category' => 'nullable|string|max:255',
        'expense_date' => 'nullable|date',
        'receipt' => 'nullable|file|max:4096',
        'description' => 'nullable|string',
    ]);

    $user = $r->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'You must be logged in to submit an expense.');
    }

    $company = $user->company;
    if (!$company) {
        return back()->with('error', 'No company is linked to your account.');
    }

    // Handle receipt upload
    $receiptPath = null;
    if ($r->hasFile('receipt')) {
        $receiptPath = $r->file('receipt')->store('receipts');
    }

    // Convert to company currency
    $amountInCompany = $currencyService->convertToCompanyCurrency(
        $r->amount,
        strtoupper($r->currency),
        strtoupper($company->default_currency ?? 'USD')
    );

    // Save expense
    $expense = Expense::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'amount' => $r->amount,
        'currency' => strtoupper($r->currency),
        'amount_in_company_currency' => $amountInCompany ?? $r->amount,
        'category' => $r->category,
        'description' => $r->description,
        'expense_date' => $r->expense_date ?? now(),
        'receipt_path' => $receiptPath,
    ]);

    // Approval workflow
    $sequence = 1;

    // Manager approval
    if ($user->manager_id && $user->is_manager_approver) {
        ExpenseApproval::create([
            'expense_id' => $expense->id,
            'approver_id' => $user->manager_id,
            'sequence' => $sequence,
        ]);
        $expense->current_approver_id = $user->manager_id;
        $expense->save();
        $sequence++;
    }

    // Finance or Admin approval
    $financeUser = User::where('role', 'manager')->where('email', 'like', '%finance%')->first();
    if ($financeUser) {
        ExpenseApproval::create([
            'expense_id' => $expense->id,
            'approver_id' => $financeUser->id,
            'sequence' => $sequence,
        ]);
    } else {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            ExpenseApproval::create([
                'expense_id' => $expense->id,
                'approver_id' => $admin->id,
                'sequence' => $sequence,
            ]);
            if (!$expense->current_approver_id) {
                $expense->current_approver_id = $admin->id;
                $expense->save();
            }
        }
    }

    return redirect()->route('expenses.index')->with('success', 'Expense submitted successfully!');
}


    public function show(Expense $expense)
    {
        // simple visibility: admin/managers or owner can view
        if (Auth::user()->role !== 'admin' && $expense->user_id !== Auth::id()) {
            // managers may view their team - optional improvement
            abort(403);
        }
        return view('expenses.show', compact('expense'));
    }
}
