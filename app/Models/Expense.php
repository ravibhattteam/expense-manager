<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $dates = ['expense_date'];

    protected $fillable = [
        'company_id',
        'user_id',
        'amount',
        'currency',
        'amount_in_company_currency',
        'category',
        'description',
        'expense_date',
        'status',
        'current_approver_id',
        'receipt_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function approvals()
    {
        return $this->hasMany(ExpenseApproval::class);
    }

    public function currentApprover()
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }
}
