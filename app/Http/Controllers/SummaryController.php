<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SummaryController extends Controller
{
    public function index(){

        $summaries = DB::table('orders')
                ->where('type', '=','bulk')
                ->select('*',
                    DB::raw('count(*) as products'),
                    DB::raw('sum(quantity) as total ')
                )
                ->groupBy('supplier_name')
                ->get();

        //dd($summaries, $summariesnew);
        $summaries = Order::where('type', 'bulk')->where('ordered', false)->groupBy('supplier_name')->get();

        $sum = Order::where('type', 'bulk')->groupBy('supplier_name')->sum('total_price');

        return view('summary.bulk', compact('summaries', 'sum'));
    }

    public function getSummaries(){

        $summary = DB::table('orders')
            ->where('type', '=', 'bulk')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->groupBy('supplier_name')
            ->where('ordered', false)
            ->get();
        return response()->json(['summary' => $summary]);
    }

    public function individual(){
        $summaries = DB::table('orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->where('type', '=','individual')
            ->groupBy('supplier_name')
            ->where('ordered', false)
            ->get();
        //dd($summaries, $summariesnew);
        foreach ($summaries as $summary){
            $summary->totalSum = 0;
        }
        foreach ($summaries as $summary){

            $summary->totalSum += number_format($summary->total_price, 2, '.', '');
        }

        return view('summary.individual', compact('summaries'));
    }


    public function analyse(Request $request){
        $orders = Order::filter()->where('ordered', true)->with('partial')->get();


        $stats = [];
        $stats['bulk'] = count(Order::filter()->where('ordered', true)->with('partial')->where('type', 'bulk')->groupBy('identifier')->get());
        $stats['individual'] = count(Order::filter()->where('ordered', true)->with('partial')->where('type', 'individual')->groupBy('identifier')->get());
        $stats['pending'] = count(Order::filter()->where('ordered', true)->with('invoice')->whereHas('invoice', function ($query){
            return $query->where('pending', '=', true);
        })->get()->groupBy('identifier'));
        $stats['complete'] = count(Order::filter()->where('ordered', true)->with('invoice')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));
        /*$stats['bulk_complete'] = count(Order::filter()->where('ordered', true)->with('invoice')->where('type', 'bulk')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));
*/
        $diff1 = Order::filter()->where('ordered', true)->with('invoice')->where('type', 'bulk')->where(function ($q){
            $q->where('complete', true)->orWhere('partial', true);
        })->orderBy('id', 'DESC')->first();
        $diff2 = Order::filter()->where('ordered', true)->with('invoice')->where('type', 'bulk')->whereHas('invoice', function ($q){
            $q->where('complete', true)->orWhere('pending', true);
        })->orderBy('id', 'DESC')->first();
        if(isset($diff1) && isset($diff2)){
            $stats['bulk_complete'] = Carbon::parse($diff1->c_date)->diffInDays(Carbon::parse($diff2->invoice[0]->order_date));

        } else {
            $stats['bulk_complete'] = '';
        }

        $diff3 = Order::filter()->where('ordered', true)->with('invoice')->where('type', 'individual')->where(function ($q){
            $q->where('complete', true)->orWhere('partial', true);
        })->orderBy('id', 'DESC')->first();
        $diff4 = Order::filter()->where('ordered', true)->with('invoice')->where('type', 'individual')->whereHas('invoice', function ($q){
            $q->where('complete', true)->orWhere('pending', true);
        })->orderBy('id', 'DESC')->first();
        if (isset($diff3) && isset($diff4)){
            $stats['individual_recived'] = Carbon::parse($diff3->c_date)->diffInDays(Carbon::parse($diff4->invoice[0]->order_date));

        } else {
            $stats['individual_recived'] = '';
        }


        $stats['individual_complete'] = count(Order::filter()->where('ordered', true)->with('invoice')->where('type', 'individual')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));

        $totalOrders = Order::filter()->where('ordered', true)->orderBy('total_price', 'DESC')->groupBy('identifier')->get();
        $totalValue =  Order::filter()->where('ordered', true)->orderBy('total_price', 'DESC')->sum('total_price');
        $stats['siteA'] = Order::filter()->where('ordered', true)->where(function ($q){
            $q->where('site', 'Clarens')->orWhere('site', 'A');
        })->sum('total_price');
        $stats['siteB'] = Order::filter()->where('ordered', true)->where(function ($q){
            $q->where('site', 'Saxon')->orWhere('site', 'B');
        })->sum('total_price');
        $suppliers = Order::filter()->where('ordered', true)->select('supplier_name')->distinct()->get();
        $stats['recived'] = count(Order::filter()->where('ordered', true)->where('complete', true)->get());
        $stats['bulk_recived'] = count(Order::filter()->where('ordered', true)->where('complete', true)->where('type', 'bulk')->get());
        /*$stats['individual_recived'] = count(Order::filter()->where('ordered', true)->where('complete', true)->where('type', 'individual')->get());
       */
        foreach ($suppliers as $supplier){
            $supplier->totalSum = Order::filter()->where('ordered', true)->where('supplier_name', $supplier->supplier_name)->sum('total_price');
        }
        $sites = Order::filter()->where('ordered', true)->select('site')->distinct()->get();
        foreach ($sites as $site){
            $site->totalSum = Order::filter()->where('ordered', true)->where('site', $site->site)->sum('total_price');
        }
        $departments = Order::filter()->where('ordered', true)->select('department')->distinct()->get();
        foreach ($departments as $department){
            $department->totalSum = Order::filter()->where('ordered', true)->where('department', $department->department)->sum('total_price');
        }
        $abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';



        return view('analyse.index', compact('orders','stats', 'totalOrders', 'totalValue', 'abc', 'suppliers', 'sites', 'departments'));
    }

    public function chart(){

        echo "<img src='https://quickchart.io/chart?bkg=white&c={type:%27bar%27,data:{labels:[´jan´,´feb´,2014,2015,2016],datasets:[{label:%27Users%27,data:[120,60,50,180,120]}]}}'>";

    }

    public function getX(){

        $xaxis = [];
        $xdata = Order::filter()->where('ordered', false)->select('created_at')->get();
        foreach ($xdata as $xdatum){
            $xaxis[] = Carbon::parse($xdatum->created_at)->format('M');
        }
        $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');




        return response()->json(['xaxis' => $xaxis, 'suppliers' => $supplier]);
    }
    public function datasets(){

        $xaxis = [];
        $xdata = Order::filter()->where('ordered', true)->select('created_at')->get();
        foreach ($xdata as $xdatum){
            $xaxis[] = Carbon::parse($xdatum->created_at)->format('M');
        }
       // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('sum(total_price) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where('ordered', true)
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
    public function groupTotal(){

        // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('count(*) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where('ordered', true)
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
    public function clarensValue(){

        // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('sum(total_price) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where(function ($q){
                    $q->where('site', 'Clarens')->orWhere('site', 'A');
                })
                ->where('ordered', true)
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
    public function clarensTotal(){

        // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('count(*) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where('ordered', true)
                ->where(function ($q){
                    $q->where('site', 'Clarens')->orWhere('site', 'A');
                })
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
    public function saxonTotal(){

        // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('count(*) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where('ordered', true)
                ->where(function ($q){
                    $q->where('site', 'Saxon')->orWhere('site', 'B');
                })
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
    public function saxonValue(){

        // $supplier = Order::filter()->where('ordered', false)->get()->groupBy('supplier_name');
        $supplier_list = Supplier::all();
        $data= [];
        foreach ($supplier_list as $value){
            $data[$value->name] = [0,0,0,0,0,0,0,0,0,0,0,0];
            $orders = Order::filter()->select(
                DB::raw('sum(total_price) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('supplier_name', $value->name)
                ->where('ordered', true)
                ->where(function ($q){
                    $q->where('site', 'Saxon')->orWhere('site', 'B');
                })
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            foreach($orders as $order){
                $tmp = [0,0,0,0,0,0,0,0,0,0,0,0];
                $data[$value->name][$order->monthKey-1] =  $order->sums;
            }
        }
        return response()->json(['datasets' => $data]);
    }
}
