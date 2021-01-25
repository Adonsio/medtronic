<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'supplier_id' => $row[0],
            'product_id' => $row[1],
            'desc' => $row[2],
            'unit' => $row[3],
            'price' => $row[4],
            'rabatt' => $row[5],
            'net_price' => $row[6],
            'price_unit' => $row[7],
            'group' => $row[8],
            'supplier_name' => Supplier::where('supplier_id', $row[0])->first()->name,
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
