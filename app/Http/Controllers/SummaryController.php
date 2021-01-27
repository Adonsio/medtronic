<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index(){

        $summaries = DB::table('bulk_orders')
                ->select('*',
                    DB::raw('count(*) as products'),
                    DB::raw('sum(quantity) total ')
                )
                ->groupBy('supplier')
                ->get();
        foreach ($summaries as $summary){
            $summary->totalSum = 0;
        }
        foreach ($summaries as $summary){
            $product = Product::where('id', $summary->product_id)->first();
            $price = $product->price;
            $rabatt = $product->rabatt;

            $summary->totalSum += number_format($summary->quantity *  ((float)$price*(float)$rabatt), 2, '.', '');
        }

        return view('summary.bulk', compact('summaries'));
    }

    public function getSummaries(){
        $summary = DB::table('bulk_orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->where('id')
            ->groupBy('supplier')
            ->get();
        return response()->json(['summary' => $summary]);
    }

    public function individual(){
        $summaries = DB::table('individual_orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->groupBy('supplier')
            ->get();
        return view('summary.individual', compact('summaries'));
    }
}
