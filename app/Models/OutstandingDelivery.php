<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class OutstandingDelivery extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = ['in', 'ordering_person'];
    protected $fillable = ['user_id', 'product_id', 'supplier_id', 'type', 'reciving_person', 'complete', 'partial', 'c_date', 'p_date'];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function Supplier(){
        return $this->hasOne(Supplier::class);
    }

    public function ordering_person($query, $value){
        return $this->user()->where('fullname', $value)->first();
    }
}
