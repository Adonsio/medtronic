<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
class Order extends Model
{
    use HasFactory, FilterQueryString;
    protected $filters = ['in'];
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
    ];
}
