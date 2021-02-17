<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
class Order extends Model
{
    use HasFactory, FilterQueryString;
    protected $filters = ['in', 'greater_or_equal', 'less_or_equal'];
    protected $fillable = [
        'identifier',
        'product_id',
        'quantity',
        'user_fullname',
        'supplier_id',
        'supplier_name',
        'desc',
        'unit',
        'price',
        'rabatt',
        'net_price',
        'price_unit',
        'group',
        'type',
        'ordered',
        'reciving_person',
        'complete',
        'partial',
        'c_date',
        'p_date',
        'user_id',
        'department',
        'site',
        'total_price'
    ];
    public function Partial(){
        return $this->hasMany(Partial::class, 'delivery_id', 'id');
    }

    public function invoice(){
        return $this->belongsToMany(Invoice::class, 'order_invoice');
    }
}
