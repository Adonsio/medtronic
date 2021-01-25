<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $password = $this->randomPassword();
        return new User([
            'login_identifier' => $row[0] . $row[1] . '-' . rand(1, 100),
            'firstname'     => $row[0],
            'lastname' => $row[1],
            'department' => $row[2],
            'fullname'=> $row[0] . ' ' . $row[1],
            'site' => $row[3],
            'password_nohash' => $password,
            'password' => Hash::make($password)
        ]);


    }
    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public function startRow(): int
    {
        return 2;
    }
}
