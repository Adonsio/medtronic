<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Invoice;
use App\Models\InvoiceFile;
use App\Models\Order;
use App\Models\OutstandingDelivery;
use App\Models\Partial;
use App\Models\PendingInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FileSystem;
use Illuminate\Support\Facades\Hash;

class DeliveryController extends Controller
{
    public function index(Request $request){
        $deliveries = Order::filter()->where('ordered', true)->with('partial')->get();
                return view('delivery.index', compact('deliveries'));
    }
    public function complete($id){
        $delivery = Order::where('id', $id)->first();
        $delivery->c_date = Carbon::now('GMT+1');
        $delivery->reciving_person = Auth::user()->id;
        $delivery->complete = true;
        $delivery->save();
        return back()->with(['success' => 'Order Complete']);
    }
    public function partial($id){
        $delivery = Order::where('id', $id)->first();
        $partial = new Partial();
        $partial->date = Carbon::now('GMT+1');
        $partial->person = Auth::user()->fullname;
        $partial->delivery_id = $delivery->id;
        $partial->save();
        $delivery->p_date = Carbon::now('GMT+1');
        $delivery->partial = true;
        $delivery->reciving_person = Auth::user()->id;
        $delivery->save();
        return back()->with(['success' => 'Order Partial']);
    }

    public function invoice(){
        $invoices = Invoice::all();
        return view('invoice.index', compact('invoices'));
    }

    public function getInvoices(){
        $invoices = Invoice::with('PendingInvoice')->get();
        return response()->json(['invoices' => $invoices]);
    }

    public function setPending(Request $request){
        $pending = new PendingInvoice();
        $pending->bill_date = Carbon::now()->format('Y-m-d');
        $pending->bill_id = $request->facture;
        $pending->bill_ammount = $request->ammount;
        $pending->invoice_id = $request->invoice['id'];
        $pending->save();
        return response()->json(['success' => 'Invoice Updated successfully']);
    }

    public function getPending(){
        $invoices = Invoice::with('PendingInvoice')->get();
        return response()->json(['pending' => $invoices]);
    }

    public function invoicelist(){

        $files = InvoiceFile::all();
        return view('invoice.list', compact('files'));
    }

    public function completeInvoice($id){
        $invoice = Invoice::where('id', $id)->first();
        $invoice->complete = true;
        $invoice->pending = false;
        $invoice->save();
    }
}
