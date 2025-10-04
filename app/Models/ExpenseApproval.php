<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'approver_id',
        'sequence',
        'status',
        'comments',
        'actioned_at'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
