<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    //
   

    protected $fillable = [
        'name',
      
        'description',
        'category_id',
        'price',
        'selling_price',
        'stock_quantity',
       

        'image',
        'status',
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'status' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= min_stock_level');
    }

    // Accessors
    public function getProfitMarginAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }
}
