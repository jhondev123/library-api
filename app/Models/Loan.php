<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoanFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'book_id', 'loan_date', 'return_date', 'devolution_date', 'status', 'delivery_status', 'observation'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'loan_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }
}
