<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Organisation;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class ListController extends Controller
{
    public function user(){
        $users = User::whereHas(
            'roles', function($q){
            $q->where('name', 'people');
        }
        )->get();
        return view('list.user', compact('users'));
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
        $products = Product::all();
        return view('list.product', compact('products'));
    }
    protected function bulkorder()
    {
        $bulkorders = BulkOrder::with(['products.supplier' => function ($query) {
            $query->select('name');
        }])->get();
        return view('list.bulkorder', compact('bulkorders'));
    }
}
