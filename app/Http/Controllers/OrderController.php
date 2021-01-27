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
        $supplier = Supplier::where('supplier_id', 2)->with('products')->get()->first();

        return view('orders.index');
    }

    public function individual(){
        $supplier = Supplier::where('supplier_id', 2)->with('products')->get()->first();

        return view('orders.individual', compact('supplier'));
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
    public function getOrders($supplier){
        $summary = DB::table('bulk_orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->groupBy('supplier')
            ->where('supplier', $supplier)
            ->first();
        $userIDs = [];
        $users = BulkOrder::where('supplier', $supplier)->groupBy('user_id')->get();
        foreach ($users as $id){
            $user = \App\Models\User::where('id', $id->user_id)->first();
            array_push($userIDs, $user);

        }
        $orders = BulkOrder::where('supplier', $supplier)->get();
        return response()->json(['orders' => $orders, 'summary' => $summary, 'user_ids' => $userIDs]);
    }

    public function getIndividualOrders($supplier){
        $summary = DB::table('individual_orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->groupBy('identifier')
            ->where('supplier', $supplier)
            ->first();
        $userIDs = [];
        $users = IndividualOrder::where('supplier', $supplier)->groupBy('user_id')->get();
        foreach ($users as $id){
            $user = \App\Models\User::where('id', $id->user_id)->first();
            array_push($userIDs, $user);

        }
        $orders = IndividualOrder::where('supplier', $supplier)->get();
        return response()->json(['orders' => $orders, 'summary' => $summary, 'user_ids' => $userIDs]);
    }

    public function updateOrder(Request $request){
        $order = BulkOrder::where('id', $request->order['id'])->first();

        $order->update([
            'quantity' => $request->quantity,
            'user_id' => $request->user_id
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
        $order = BulkOrder::where('id', $id);
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
}
