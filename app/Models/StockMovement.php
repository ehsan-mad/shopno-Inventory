<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    //
    protected $fillable = [
        'product_id',
        'quantity',
        'type', // 'in' or 'out'
        'previous_stock',
        'current_stock',
        'user_id',
    ];
  

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
