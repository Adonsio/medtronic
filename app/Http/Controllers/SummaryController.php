<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Order;
use App\Models\Product;
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
}
