<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\User;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class SetupController extends Controller
{
    public function index(){
        $user = User::first();
        $role = Role::where('name', 'superuser')->first();
        if($role === null){
            Role::create(['name' => 'superuser']);
        }
        $rolePeople = Role::where('name', 'people')->first();
        if($rolePeople === null){
            Role::create(['name' => 'people']);
        }
        $user->assignRole('superuser');
        $setup = new Setup();
        $setup->done = true;
        $setup->save();
        return back()->with(['success' => 'Setup Completed']);
    }
}
