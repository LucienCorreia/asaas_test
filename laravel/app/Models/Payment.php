<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'customer_id',
        'status',
        'code',
        'billing_type',
        'value',
        'due_date',
        'bank_slip_url',
        'billing_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
