<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['coupon','supplier_name','department','user_fullname','net_price','gross_price','total_price','order_date','complete','pending'];


    public function PendingInvoice(){
        return $this->hasMany(PendingInvoice::class, 'invoice_id', 'id');
    }


    public function order(){
        return $this->belongsToMany(Order::class, 'order_invoice');
    }

}
