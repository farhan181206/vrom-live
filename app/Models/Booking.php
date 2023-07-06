<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'start-date',
        'end-date',
        'address',
        'city',
        'zip',
        'status',
        'payment_method',
        'payment_status',
        'payment_url',
        'item_id',
        'user_id'
    ];

    protected $dates = [
        'start-date',
        'end-date'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
