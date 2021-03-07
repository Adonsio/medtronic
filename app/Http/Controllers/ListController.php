<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Invoice;
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
        $bulkorders = Order::where('type', 'bulk')->where('ordered', false)->get();
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

    public function meta(){
        return view('list.meta');
    }


    public function exportBulk(){
        $fileName = 'bulk.csv';
        $orders =  Order::where('type', 'bulk')->where('ordered', false)->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Identifier', 'Department', 'Site', 'Product ID', 'Group', 'Quantity', 'User Fullname', 'Description', 'Unit', 'Price', 'Net Price', 'Price Unit', 'Total Price');

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Identifier']  = $order->identifier;
                $row['Department']    = $order->department;
                $row['Site']    = $order->site;
                $row['Product ID']  = $order->product_id;
                $row['Group']  = $order->group;
                $row['Quantity'] = $order->quantity;
                $row['User Fullname'] = $order->user_fullname;
                $row['Description'] = $order->desc;
                $row['Unit'] = $order->unit;
                $row['Price'] = $order->price;
                $row['Net Price'] = $order->net_price;
                $row['Price Unit'] = $order->price_unit;
                $row['Total Price'] = $order->total_price;

                fputcsv($file, array($row['Identifier'],$row['Department'],$row['Site'],$row['Product ID'],$row['Group'],$row['Quantity'],$row['User Fullname'],$row['Description'],$row['Unit'],$row['Price'], $row['Net Price'], $row['Price Unit'], $row['Total Price']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportOutstanding(){
        $fileName = 'outstandingdeliveries.csv';
        $orders =  Order::filter()->where('ordered', true)->with('partial')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Donneur d\'ordre', 'Nom Fournisseur', '# Produit', 'Description Produit', 'Unité', 'Prix brut', 'Rabais', 'Prix net', 'Prix/Unité', 'Commander des packages', 'Total Units Ordered', 'Nombre total d\'unités commandées', 'Groupe de produits', 'Site', 'Personne recevante', 'Livraison complète','Livraison partielle');
        $callback = function() use($orders, $columns) {

            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($orders as $order) {

               //$user = User::where('id', $order->user_id)->first();
                //$user2 =  User::where('id', $order->reciving_person)->first();
                $row['one']  = $order->user->fullname;
                $row['two']    = $order->supplier_name;
                $row['three']    = $order->product_id;
                $row['four']  = $order->desc;
                $row['five']  = $order->unit;
                $row['six'] = $order->price;
                $row['seven'] = number_format((float)$order->rabatt, 2, '.', '')*100 ;
                $row['eight'] = number_format((float)$order->net_price, 2, '.', '') ;
                  $row['nine'] = number_format((float)$order->price_unit, 2, '.', '');
                  $row['ten'] = $order->quantity;
                  $row['eleven'] = ($order->unit * $order->quantity);
                  $row['twelve'] = number_format((float)$order->total_price, 2, '.', '');
                  $row['thirteen'] = $order->group;
                  $row['fourteen'] = $order->site;
                  if($order->recivingperson){
                      $row['fifteen'] = $order->recivingperson->fullname;
                  } else {
                      $row['fifteen'] = '';
                  }
                    // $row['fifteen'] = $order->recivingperson->fullname ? $order->recivingperson->fullname : '';
                 $row['sixteen'] = $order->c_date ? $order->c_date : '';
                 $row['seventeen'] = $order->p_date ? $order->p_date : '';
                fputcsv($file, array($row['one'],
                                     $row['two'],
                                     $row['three'],
                                     $row['four'],
                                     $row['five'],
                                     $row['six'],
                                     $row['seven'],
                     $row['eight'],
                      $row['nine'],
                      $row['ten'],
                      $row['eleven'],
                      $row['twelve'],
                      $row['thirteen'],
                      $row['fourteen'],
                      $row['fifteen'],
                       $row['sixteen'],
                       $row['seventeen'],
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportInvoice(){
        $fileName = 'invoices.csv';
        //$orders =  Order::filter()->where('ordered', true)->with('partial')->get();
        $orders = Invoice::with('PendingInvoice')->get();
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('#Bon de Commande', 'Montant HT', 'Montant TTC', 'Fournisseur', 'Date de la commande', 'Complete / Pending', '# Facture', 'Montant Facture', 'Date');
        foreach ($orders as $order){
            if ($order->PendingInvoice){
                array_push($columns, '#Facture', 'Montant', 'Date');
            }

        }

        $callback = function() use($orders, $columns) {

            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($orders as $key => $order) {

                //$user = User::where('id', $order->user_id)->first();
                //$user2 =  User::where('id', $order->reciving_person)->first();
                $row['one']  = $order->coupon;
                $row['two']    = $order->total_price;
                $row['three']    = $order->gross_price;
                $row['four']  = $order->supplier_name;
                $row['five']  = $order->order_date;
                if($order->pending){
                    $row['six'] = 'Pending';
                } else {
                    $row['six'] = 'Complete';
                }

                if ($order->PendingInvoice){
                    $i = count($order->PendingInvoice)-1;
                    $row['seven'] = $order->PendingInvoice[0]->bill_id;
                    $row['eight'] = $order->PendingInvoice[0]->bill_ammount;
                    $row['nine'] = $order->PendingInvoice[0]->bill_date;
                } else{
                    $row['seven'] = ' ';
                    $row['eight'] = ' ';
                    $row['nine'] = ' ';
                }
                // $row['fifteen'] = $order->recivingperson->fullname ? $order->recivingperson->fullname : '';

                $outputarray = array(
                    $row['one'],
                    $row['two'],
                    $row['three'],
                    $row['four'],
                    $row['five'],
                    $row['six'],
                    $row['seven'],
                    $row['eight'],
                    $row['nine'],
                    );
                foreach ($order->PendingInvoice as $item){
                        array_push($outputarray, $item->bill_id, $item->bill_ammount, $item->bill_date);
                }
                fputcsv($file, $outputarray
                );
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
