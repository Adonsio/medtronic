<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\File;
use App\Models\IndividualOrder;
use App\Models\Invoice;
use App\Models\InvoiceFile;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use function Symfony\Component\String\b;

class CouponController extends Controller
{
    public function printIndivudual($identifier){

        $user = Auth::user();
        $site = $user->site;
        $fullname = $user->fullname;
        $price = Order::where('identifier', $identifier)->sum('total_price');

        $data = [];
        $data['price'] = $price;
        $data['site'] = $site;
        $data['fullname'] = $fullname;
        $supplier_id = Order::where('identifier', $identifier)->first();
        $supplier = Supplier::where('supplier_id', $supplier_id->supplier_id)->first();
        $orders = Order::where('identifier', $identifier)->get();
        $order_ids = [];
        $data['net_price'] = 0;
        $data['gross_price'] = 0;
        foreach ($orders as $key => $order){
            $order_ids[] = $order->id;
            $data['net_price'] += ($order->net_price * $order->quantity);
            $data['gross_price'] += ($order->price * $order->quantity);
            $order->ordered = true;
            $order->save();
        }

        view()->share('coupon.coupon', $data);
        $pdf = PDF::loadView('coupon.individual', ['data' => $data, 'orders' => $orders, 'supplier' => $supplier]);
        $filename = Carbon::now('GMT+1')->format('d.m.Y'). '-'. $identifier .'-'. $supplier->name.'-I.pdf';
        $invoiceFile = new InvoiceFile();
        $invoiceFile->path = 'invoice/'.$filename;
        $invoiceFile->name = $filename;
        $invoiceFile->save();
        $pdf->save(public_path( 'invoice/'.$filename));
        $invoice = new Invoice();
        $invoice->coupon = $identifier;
        $invoice->supplier_name = $supplier->name;
        $invoice->department = $user->department;
        $invoice->user_fullname = $fullname;
        $invoice->gross_price = $data['gross_price'];
        $invoice->net_price = $data['net_price'];
        $invoice->total_price = $data['price'];
        $invoice->order_date = Carbon::now()->format('Y-m-d');
        $invoice->complete = false;
        $invoice->pending = true;
        $invoice->save();
        $invoice->order()->attach($order_ids);

        return back()->with(['success' => 'Coupon créé']);

    }

    public function bulk(){
        $orders = Order::where('type', 'bulk')->where('ordered', false)->get()->groupBy('supplier_name');
        $users = User::all();
        return view('coupon.bulk', compact('orders', 'users'));
    }

    public function createIndividual($identifier){
        $order = Order::where('identifier', $identifier)->
        get();
        $users = User::all();

        return view('coupon.create', compact('order', 'users'));
    }
    public function createBulk(){
        $supplier_names = Order::select('supplier_name')->where('type', 'bulk')->where('ordered', false)->distinct()->get()->pluck('supplier_name');
        $pdf = [];
        $user = Auth::user();
        $site = $user->site;
        $fullname = $user->fullname;

        $data = [];
        $data['site'] = $site;
        $data['fullname'] = $fullname;


        foreach ($supplier_names as $key => $supplier_name){
            $products = [];
            $uniqueOrders = [];
            $identifier = $this->randomPassword();
            $orders = Order::where('type', 'bulk')->where('ordered', false)->where('supplier_name', $supplier_name)->get();
            $data['price'] =  Order::where('supplier_name', $supplier_name)->where('ordered', false)->where('type', 'bulk')->sum('total_price');
            $data['net_price'] = 0;
            $data['gross_price'] = 0;
            $supplier = Supplier::where('name', $supplier_name)->first();
            $order_ids = [];
            foreach ($orders as $key => $order){
                $order_ids[] = $order->id;
                $id = $order->product_id;
                if (array_key_exists($id, $products)){
                    $products[$id] += $order->quantity;
                } else
                {
                    $products[$id] = $order->quantity;
                    $uniqueOrders[] = $order;
                }
                $data['net_price'] += ($order->net_price * $order->quantity);
                $data['gross_price'] += ($order->price * $order->quantity);
            }

            $pdf[$key] = PDF::loadView('coupon.coupon', ['data' => $data, 'uniqueOrders' => $uniqueOrders, 'supplier' => $supplier, 'products' => $products]);
            $filename = Carbon::now('GMT+1')->format('d.m.Y'). '-'. $identifier .'-'. $supplier_name.'-G.pdf';
            $invoiceFile = new InvoiceFile();
            $invoiceFile->path = 'invoice/'.$filename;
            $invoiceFile->name = $filename;
            $invoiceFile->save();
            $pdf[$key]->save(public_path('invoice/'.$filename ));
            $invoice = new Invoice();
            $invoice->coupon = $identifier;
            $invoice->supplier_name = $supplier_name;
            $invoice->department = $user->department;
            $invoice->user_fullname = $fullname;
            $invoice->gross_price = $data['gross_price'];
            $invoice->net_price = $data['net_price'];
            $invoice->total_price = $data['price'];
            $invoice->order_date = Carbon::now()->format('Y-m-d');
            $invoice->complete = false;
            $invoice->pending = true;
            $invoice->save();
            $invoice->order()->attach($order_ids);
        }
        $orders = Order::where('type', 'bulk')->where('ordered', false)->where('supplier_name', $supplier_name)->get();
        foreach ($orders as $order){
            $order->ordered = true;
            $order->save();
        }
        return back()->with(['success' => 'Coupons créé']);

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

    public function download($id){
        $file = InvoiceFile::where('id', $id)->first();
        return response()->download(public_path($file->path));
    }
}

class product{
    public $id;
    public $quantity;
}
