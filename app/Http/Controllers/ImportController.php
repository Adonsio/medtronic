<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrgaImport;
use App\Imports\SupplierImport;
use App\Imports\ProductImport;
use App\Models\User;
use function PHPUnit\Framework\isNull;
use Spatie\Permission\Models\Role;
class ImportController extends Controller
{
    public function users($file)
    {
        $filePath = File::where('id', $file)->first();
        $filePath->imported = true;
        $filePath->save();
        Excel::import(new UsersImport, storage_path('/app/public/'.$filePath->path));
        $users = User::doesntHave('roles')->get();

        foreach ($users as $user){
            $user->syncRoles('people');
        }
        return redirect('/file/upload')->with('success', 'Users imported!');
    }

    public function orgas($file){
        $filePath = File::where('id', $file)->first();
        $filePath->imported = true;
        $filePath->save();

        Excel::import(new OrgaImport, storage_path('/app/public/'.$filePath->path));

        return redirect('/file/upload')->with('success', 'Organisations imported!');
    }

    public function product($file){
        $products = Product::all();
        if ($products){
            foreach ($products as $product){
                $product->active = false;
                $product->save();
            }
        }
        $supplier = Supplier::all();
        if (!count($supplier)){
            return redirect('/file/upload')->with('error', 'Import Suppliers first!');
        }
        $filePath = File::where('id', $file)->first();
        $filePath->imported = true;
        $filePath->save();
        Excel::import(new ProductImport, storage_path('/app/public/'.$filePath->path));

        return redirect('/file/upload')->with('success', 'Product Catalouge imported!');
    }

    public function supplier($file){
        $filePath = File::where('id', $file)->first();
        $filePath->imported = true;
        $filePath->save();
        Excel::import(new SupplierImport, storage_path('/app/public/'.$filePath->path));

        return redirect('/file/upload')->with('success', 'Suppliers imported!');
    }

}
