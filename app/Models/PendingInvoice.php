<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingInvoice extends Model
{
    use HasFactory;
    protected $fillable = ['bill_date', 'bill_id', 'bill_ammount', 'invoice_id'];

    public function Invoice(){
        return $this->belongsTo(Invoice::class, 'id', 'invoice_id');
    }
}
