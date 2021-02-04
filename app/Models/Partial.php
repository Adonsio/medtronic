<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partial extends Model
{
    use HasFactory;
    protected $fillable = ['delivery_id', 'date', 'person'];

    public function delivery() {
        return $this->belongsTo(Order::class, 'id', 'delivery_id');
    }
}
