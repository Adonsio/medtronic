<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\IndividualOrder;
use App\Models\Order;
use App\Models\OutstandingDelivery;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.index');
    }

    public function individual(){
        return view('orders.individual');
    }

    public function addbulk(Request $request){

        $identifier = $this->randomPassword();
        foreach ($request->bulkorder as $key => $item){
            $quantity = $request->bulkorder[$key]['quantity'];
            $product_id = $request->bulkorder[$key]['product']['id'];
            $user_id = $request->bulkorder[$key]['user']['id'];
            $supplier = $request->bulkorder[$key]['supplier'];
            BulkOrder::create(['product_id' => $product_id, 'quantity' => $quantity,  'user_id' => $user_id, 'supplier' => $supplier, 'identifier' => $identifier]);

            OutstandingDelivery::create(['user_id' => $user_id, 'product_id' => $product_id, 'supplier_id' => $supplier, 'type' => 'bulk', 'complete' => false, 'partial' => false, 'quantity' => $quantity]);

        }



        return response()->json(['success' => 'Bulk Order Created']);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIndividual(Request $request){

        $identifier = $this->randomPassword();
        foreach ($request->individualorder as $key => $item){
            $quantity = $request->individualorder[$key]['quantity'];
            $product_id = $request->individualorder[$key]['product']['id'];
            $user_id = $request->individualorder[$key]['user']['id'];
            $supplier = $request->individualorder[$key]['supplier'];

            IndividualOrder::create(['product_id' => $product_id, 'quantity' => $quantity,  'user_id' => $user_id, 'supplier' => $supplier, 'identifier' => $identifier]);
            OutstandingDelivery::create(['user_id' => $user_id, 'product_id' => $product_id, 'supplier_id' => $supplier, 'type' => 'individual', 'complete' => false, 'partial' => false, 'quantity' => $quantity]);
        }
        return response()->json(['success' => 'Indivudal Order Created']);

    }

    /**
     * @return string
     */
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
    /**
     * @param $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders($supplier, $type){
        $summary = DB::table('orders')
            ->where('type', '=', $type)
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) as total ')
            )
            ->groupBy('supplier_name')
            ->where('ordered', false)
            ->where('supplier_id', $supplier)
            ->get();
        $userIDs = [];
        foreach ($summary as $item){
            $item->totalSum = 0;
        }
        foreach ($summary as $item){

            $item->totalSum += number_format($item->total_price, 2, '.', '');
        }
        $users = Order::where('supplier_id', $supplier)->where('type', $type)->groupBy('user_fullname')->get();
        foreach ($users as $id){
            $user = \App\Models\User::where('fullname', $id->user_fullname)->first();
            array_push($userIDs, $user);

        }
        $sum = Order::where('type', $type)->where('ordered', false)->where('supplier_id', $supplier)->sum('total_price');
        $sites = User::select('site')->distinct()->get();
        $departments = User::select('department')->distinct()->get();
        $orders = Order::where('supplier_id', $supplier)->where('ordered', false)->where('type', $type)->get();
        $transport = Supplier::select('transport')->where('supplier_id', $supplier)->first();
        return response()->json(['orders' => $orders, 'summary' => $summary, 'user_ids' => $userIDs, 'sites' => $sites, 'departments' => $departments, 'sum' => $sum, 'transport' => $transport]);
    }

    public function getIndividualOrders($supplier){
        $summary = DB::table('orders')
            ->where('type','=','individual')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->groupBy('identifier')
            ->where('supplier_id', $supplier)
            ->first();
        $userIDs = [];
        $users = Order::where('supplier_id', $supplier)->groupBy('user_id')->get();
        foreach ($users as $id){
            $user = \App\Models\User::where('id', $id->user_id)->first();
            array_push($userIDs, $user);

        }
        $orders = IndividualOrder::where('supplier', $supplier)->get();
        return response()->json(['orders' => $orders, 'summary' => $summary, 'user_ids' => $userIDs]);
    }

    public function updateOrder(Request $request){
        $order = Order::where('id', $request->order['id'])->first();
        $user = User::where('id', $request->user_id)->first();
        $order->update([
            'quantity' => $request->quantity,
            'user_id' => $request->user_id,
            'user_fullname' => $user->fullname,
            'department' => $request->department,
            'site' => $request->site,
            'total_price' => (number_format((float)$order->price * $order->rabatt, 2, '.', '') * $request->quantity)
        ]);
        $order->save();

    }
    public function updateIndividualOrder(Request $request){
        $order = IndividualOrder::where('id', $request->order['id'])->first();

        $order->update([
            'quantity' => $request->quantity,
            'user_id' => $request->user_id
        ]);
        $order->save();

    }

    public function deleteOrder($id){
        $order = Order::where('id', $id);
        $order->delete();

    }
    public function deleteIndividualOrder($id){
        $order = IndividualOrder::where('id', $id);
        $order->delete();

    }

    public function getSites(){
        $sites = User::select('site')->distinct()->get();
        return response()->json(['sites' => $sites]);
    }
    public function getDepartments(){
        $departments = User::select('department')->distinct()->get();
        return response()->json(['departments' => $departments]);
    }
    public function getGroups(){
        $groups = Product::select('group')->distinct()->get();
        return response()->json(['groups' => $groups]);
    }

    public function createOrder(Request $request){
        $identifier = $this->randomPassword();
        foreach ($request->order as $key => $item){



            $quantity = $request->order[$key]['quantity'];
            $product_id = $request->order[$key]['product']['id'];
            $user_id = $request->order[$key]['user']['id'];
            $supplier_id = $request->order[$key]['supplier'];
            $type = $request->order[$key]['type'];

            $product = Product::where('id', $product_id)->first();
            $user = User::where('id', $user_id)->first();
            $supplier = Supplier::where('id', $supplier_id)->first();
            $order = new Order();
            $order->user_id = $user->id;
            $order->identifier = $identifier;
            $order->product_id = $product->product_id;
            $order->quantity = $quantity;
            $order->user_fullname = $user->fullname;
            $order->supplier_id = $supplier->supplier_id;
            $order->supplier_name = $supplier->name;
            $order->desc = $product->desc;
            $order->unit = $product->unit;
            $order->price = $product->price;
            $order->rabatt = $product->rabatt;
            $order->net_price = number_format((float)$product->price * $product->rabatt, 2, '.', '');
            $order->price_unit = number_format((float)($product->price * $product->rabatt)/$product->unit, 2, '.', '');
            $order->total_price = (number_format((float)$product->price * $product->rabatt, 2, '.', '') * $quantity);
            $order->group = $product->group;
            $order->type = $type;
            $order->ordered = false;
            $order->complete = false;
            $order->partial = false;
            $order->department = $user->department;
            $order->site = $user->site;
            $order->save();


            //IndividualOrder::create(['product_id' => $product_id, 'quantity' => $quantity,  'user_id' => $user_id, 'supplier' => $supplier, 'identifier' => $identifier]);
            //OutstandingDelivery::create(['user_id' => $user_id, 'product_id' => $product_id, 'supplier_id' => $supplier, 'type' => 'individual', 'complete' => false, 'partial' => false, 'quantity' => $quantity]);
        }
        $orderType = 'commande groupée';
        if ($type == 'individual'){
            $orderType = 'commande individuelle';
        }
        return response()->json(['success' => ''. $orderType .' créé']);
    }

    public function getProducts($id, $fullname){
        $products = Product::where('supplier_id', $id+1)->where('user_fullname', null)->where('active', true)->get();
        $user_products = Product::where('supplier_id', $id+1)->where('user_fullname', $fullname)->where('active', true)->get();

        return response()->json(['products' => $products, 'user_products' => $user_products]);
    }
}
