<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'code',
        'name',
        'document_number',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
