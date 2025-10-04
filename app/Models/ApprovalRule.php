<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'rule_type',
        'threshold',
        'specific_user_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function specificApprover()
    {
        return $this->belongsTo(User::class, 'specific_user_id');
    }
}
