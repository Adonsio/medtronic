<?php

namespace App\Imports;

use App\Models\Organisation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrgaImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Organisation([
            'name' => $row[0],
            'site' => $row[1],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
