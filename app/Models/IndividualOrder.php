<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualOrder extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity', 'user_id', 'supplier', 'identifier'];
    protected $with = ['products', 'user'];
    public function Products(){
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
