<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
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
        return view('summary.bulk', compact('summaries'));
    }

    public function getSummaries(){
        $summary = DB::table('bulk_orders')
            ->select('*',
                DB::raw('count(*) as products'),
                DB::raw('sum(quantity) total ')
            )
            ->where('id',)
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
