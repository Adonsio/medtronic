<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class OutstandingDelivery extends Model
{
    use HasFactory, FilterQueryString;
    protected $with = ['partial'];
    protected $filters = ['in'];
    protected $fillable = ['user_id', 'product_id', 'supplier_id', 'type', 'reciving_person', 'complete', 'partial', 'c_date', 'p_date'];
    protected $casts = [
        'p_date' => 'array',
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function Supplier(){
        return $this->hasOne(Supplier::class);
    }

    public function Partial(){
        return $this->hasMany(Partial::class, 'delivery_id', 'id');
    }



}
