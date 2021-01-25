<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\IndividualOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use function Symfony\Component\String\b;

class CouponController extends Controller
{
    public function printIndivudual($identifier, $id){
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $bulk = IndividualOrder::with(['products.supplier' => function ($query) {
            $query->select('name');
        }])->get();
        $user = User::where('id', $id)->first();
        $orders = IndividualOrder::where('identifier', $identifier)->get();
        $count = count($bulk);
        $site = $user->site;
        $fullname = $user->fullname;
        $price = 0;
        foreach ($orders as $order){
            $price += ($order->products[0]->price * $order->products[0]->rabatt );
        }
        $date = Carbon::now('GMT+1')->format('d.m.Y');
        $templateProcessor = new TemplateProcessor(storage_path('helloworld.docx'));
        $templateProcessor->setValues(array('date' => $date, 'site' => $site, 'fullname' => $fullname, 'price' => number_format((float)$price, 2, '.', ''). '€'));
        $templateProcessor->cloneRow('data', $count);
        foreach ($bulk as $key => $value) {
            $netprice = number_format((float)$value->products[0]->price * $value->products[0]->rabatt, 2, '.', '');
            $total = number_format((float)$value->products[0]->price * $value->products[0]->rabatt, 2, '.', '') * $value->quantity;
            $templateProcessor->setValue('data#'. ($key+1) .'', $value->products[0]->product_id );
            $templateProcessor->setValue('desc#'. ($key+1) .'', $value->products[0]->desc );
            $templateProcessor->setValue('unit#'. ($key+1) .'', $value->products[0]->unit );
            $templateProcessor->setValue('netprice#'. ($key+1) .'', $netprice );
            $templateProcessor->setValue('quantity#'. ($key+1) .'', $value->quantity );
            $templateProcessor->setValue('total#'. ($key+1) .'', $total. '€' );
            // (product.price * product.rabatt)
        }
     $templateProcessor->saveAs(storage_path('updated.docx'));
        return back()->with(['success' => 'Coupon created']);
    }

    public function createIndividual($identifier){
        $order = IndividualOrder::where('identifier', $identifier)->get();
        $users = User::all();
        return view('coupon.create', compact('order', 'users'));
    }
    public function createBulk($identifier){
        $order = BulkOrder::where('identifier', $identifier)->get();
        $users = User::all();
        $bulk = true;
        return view('coupon.create', compact('order', 'bulk', 'users'));
    }
}
