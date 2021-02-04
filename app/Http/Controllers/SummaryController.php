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
        $summaries = Order::where('type', 'bulk')->groupBy('supplier_name')->get();

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
        $orders = Order::filter()->with('partial')->get();


        $stats = [];
        $stats['bulk'] = count(Order::filter()->with('partial')->where('type', 'bulk')->groupBy('identifier')->get());
        $stats['individual'] = count(Order::filter()->with('partial')->where('type', 'individual')->groupBy('identifier')->get());
        $stats['pending'] = count(Order::filter()->with('invoice')->whereHas('invoice', function ($query){
            return $query->where('pending', '=', true);
        })->get()->groupBy('identifier'));
        $stats['complete'] = count(Order::filter()->with('invoice')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));
        $stats['bulk_complete'] = count(Order::filter()->with('invoice')->where('type', 'bulk')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));
        $stats['individual_complete'] = count(Order::filter()->with('invoice')->where('type', 'individual')->whereHas('invoice', function ($query){
            return $query->where('complete', '=', true);
        })->get()->groupBy('identifier'));
        $totalOrders = Order::filter()->orderBy('total_price', 'DESC')->groupBy('identifier')->get();
        $totalValue =  Order::filter()->orderBy('total_price', 'DESC')->sum('total_price');
        $stats['siteA'] = Order::filter()->where('site', 'A')->sum('total_price');
        $stats['siteB'] = Order::filter()->where('site', 'B')->sum('total_price');
        $suppliers = Order::filter()->select('supplier_name')->distinct()->get();
        $stats['recived'] = count(Order::filter()->where('complete', true)->get());
        $stats['bulk_recived'] = count(Order::filter()->where('complete', true)->where('type', 'bulk')->get());
        $stats['individual_recived'] = count(Order::filter()->where('complete', true)->where('type', 'individual')->get());
        foreach ($suppliers as $supplier){
            $supplier->totalSum = Order::filter()->where('supplier_name', $supplier->supplier_name)->sum('total_price');
        }
        $sites = Order::filter()->select('site')->distinct()->get();
        foreach ($sites as $site){
            $site->totalSum = Order::filter()->where('site', $site->site)->sum('total_price');
        }
        $departments = Order::filter()->select('department')->distinct()->get();
        foreach ($departments as $department){
            $department->totalSum = Order::filter()->where('department', $department->department)->sum('total_price');
        }
        $abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return view('analyse.index', compact('orders','stats', 'totalOrders', 'totalValue', 'abc', 'suppliers', 'sites', 'departments'));
    }

    public function chart(){

        echo "<img src='https://quickchart.io/chart?bkg=white&c={type:%27bar%27,data:{labels:[´jan´,´feb´,2014,2015,2016],datasets:[{label:%27Users%27,data:[120,60,50,180,120]}]}}'>";

    }
}
