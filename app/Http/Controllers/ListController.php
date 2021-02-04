<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Order;
use App\Models\Organisation;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use ConsoleTVs\Charts\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class ListController extends Controller
{
    public function user(){
        $users = User::all();
        $roles = Role::all();
        return view('list.user', compact('users', 'roles'));
    }
    public function organisations(){
        $orgas = Organisation::all();
        return view('list.orga', compact('orgas'));
    }

    public function suppliers(){
        $suppliers = Supplier::all();
        return view('list.supplier', compact('suppliers'));
    }

    public function products(){
        $products = Product::where('active', true)->where('user_fullname', null)->get();
        return view('list.product', compact('products'));
    }
    public function bulkorder()
    {
        $bulkorders = Order::where('ordered', false)->where('type', 'bulk')->get();
        return view('list.bulkorder', compact('bulkorders'));
    }

    public function changeRole(Request $request,$id){
        $user = User::where('id', $id)->first();

        $user->syncroles($request->role);

        return back()->with(['success' => 'Role Changed']);
    }

    public function resetPassword($id){
        $user = User::where('id', $id)->first();
        $password = $this->randomPassword();
        $user->password = Hash::make($password);
        $user->password_nohash = $password;
        $user->save();
        return back()->with(['success' => 'Password was reset']);
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

    public function editUser($id){
        $user = User::where('id', $id)->first();
        $roles = Role::all();

        return view('list.edituser', compact('user', 'roles'));

    }

    public function updateUser(Request $request, $id){
        $user = User::where('id', $id)->first();

        $user->update($request->all());

        return back()->with(['success' => 'User Information was updated']);
    }
}
