<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRule;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalRuleController extends Controller
{
    public function create()
    {
        $users = User::where('company_id', auth()->user()->company_id)->get();
        return view('admin.rules.create', compact('users'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'rule_type'=>'required|in:percentage,specific,hybrid',
            'threshold'=>'nullable|numeric',
            'specific_user_id'=>'nullable|exists:users,id'
        ]);

        ApprovalRule::updateOrCreate(
            ['company_id' => auth()->user()->company_id],
            [
                'rule_type' => $r->rule_type,
                'threshold' => $r->threshold,
                'specific_user_id' => $r->specific_user_id
            ]
        );

        return redirect()->back()->with('success', 'Rule saved');
    }
}
