<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use App\Models\ApprovalRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pending = Expense::where('current_approver_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('approvals.index', compact('pending'));
    }

    public function approve(Request $r, Expense $expense)
    {
        $user = $r->user();
        if ($expense->current_approver_id !== $user->id) {
            abort(403, 'Not your approval task.');
        }

        $approval = ExpenseApproval::where('expense_id', $expense->id)
            ->where('approver_id', $user->id)
            ->first();

        if (! $approval) abort(404);

        $approval->update([
            'status' => 'approved',
            'comments' => $r->comments ?? null,
            'actioned_at' => now()
        ]);

        // Check approval rule
        $rule = ApprovalRule::where('company_id', $expense->company_id)->first();
        if ($rule) {
            if ($rule->rule_type === 'specific' && $rule->specific_user_id == $user->id) {
                $expense->update(['status' => 'approved', 'current_approver_id' => null]);
                return back()->with('success', 'Expense approved (specific approver).');
            }

            if (in_array($rule->rule_type, ['percentage','hybrid'])) {
                $total = ExpenseApproval::where('expense_id', $expense->id)->count();
                $approved = ExpenseApproval::where('expense_id', $expense->id)->where('status', 'approved')->count();
                $percent = $total ? ($approved / $total) * 100 : 0;
                if ($rule->threshold && $percent >= $rule->threshold) {
                    $expense->update(['status' => 'approved', 'current_approver_id' => null]);
                    return back()->with('success', 'Expense approved via threshold.');
                }
            }
        }

        // move to next approver
        $next = ExpenseApproval::where('expense_id', $expense->id)
            ->where('status', 'pending')
            ->orderBy('sequence')
            ->first();

        if ($next) {
            $expense->update(['current_approver_id' => $next->approver_id]);
        } else {
            $expense->update(['status' => 'approved', 'current_approver_id' => null]);
        }

        return back()->with('success', 'Approved');
    }

    public function reject(Request $r, Expense $expense)
    {
        $user = $r->user();
        if ($expense->current_approver_id !== $user->id) {
            abort(403, 'Not your approval task.');
        }

        $approval = ExpenseApproval::where('expense_id', $expense->id)
            ->where('approver_id', $user->id)
            ->first();

        if (! $approval) abort(404);

        $approval->update([
            'status' => 'rejected',
            'comments' => $r->comments ?? null,
            'actioned_at' => now()
        ]);

        $expense->update(['status' => 'rejected', 'current_approver_id' => null]);

        return back()->with('success', 'Rejected');
    }
}
