<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $fillable =[
        'customer_id',
        'user_id',
        'total',
        'paid',
        'due',
        'discount',
        'payment',
        'status',
        'tax',
        
    ];

   public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Scopes
    
}
