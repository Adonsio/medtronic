<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'name', 'street', 'zip_code', 'city', 'phone', 'fax', 'contact_person'];

    public function Products(){
        return $this->hasMany(Product::class, 'supplier_id', 'supplier_id' );
    }
}
