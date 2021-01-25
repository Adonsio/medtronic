<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $with = ['supplier'];
    protected $fillable = ['supplier_id', 'product_id', 'desc', 'unit', 'price', 'rabatt', 'net_price', 'price_unit', 'group', 'supplier_name'];
    public function Supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
    public function Delivery(){
        return $this->hasMany(OutstandingDelivery::class, 'product_id', 'id');
    }
}
