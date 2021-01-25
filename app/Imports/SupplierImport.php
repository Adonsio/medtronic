<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SupplierImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Supplier([
            'supplier_id' => $row[0],
            'name' => $row[1],
            'street' => $row[2],
            'zip_code' => $row[3],
            'phone' => $row[4],
            'fax' => $row[5],
            'contact_person' => $row[6],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
