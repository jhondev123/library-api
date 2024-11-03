<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    /** @use HasFactory<\Database\Factories\FineFactory> */
    use HasFactory;

    protected $fillable = ['loan_id', 'value', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'value' => 'float',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
