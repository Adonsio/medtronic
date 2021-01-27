<?php

namespace App\Http\Controllers;

use App\Models\OutstandingDelivery;
use App\Models\Partial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeliveryController extends Controller
{
    public function index(Request $request){
        $deliveries = OutstandingDelivery::filter()->get();

        if ($request->has('site', 'department','group')){

            $group = $request->group;
            $value = $request->site;
            $department = $request->department;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')
                ->whereHas('user', function ($query) use($value, $department){
                $query->where('site', $value )->where('department', $department);
                })
                ->whereHas('product', function ($query) use($group){
                    $query->where('group', $group);
                })
                ->get();
        } elseif ($request->has('department','site')){
            $value = $request->site;
            $department = $request->department;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')->whereHas('user', function ($query) use($value, $department){
                $query->where('site', $value)->where('department', $department);
            })->get();

        } elseif ($request->has('site', 'group')){
            $group = $request->group;
            $value = $request->site;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')
                ->whereHas('user', function ($query) use($value){
                $query->where('site', $value);
                })
                ->whereHas('product', function ($query) use($group){
                    $query->where('group', $group);
                })
                ->get();


        } elseif($request->has('department', 'group')){
            $group = $request->group;
            $value = $request->department;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')
                ->whereHas('user', function ($query) use($value){
                $query->where('department', $value);
                })
                ->whereHas('product', function ($query) use($group){
                    $query->where('group', $group);
                })
                ->get();

        } elseif ($request->has('site')){
            $value = $request->site;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')->whereHas('user', function ($query) use($value){
                $query->where('site', $value);
            })->get();
        }elseif ($request->has('department')){
            $value = $request->department;
            $deliveries = OutstandingDelivery::filter()->with('user', 'product')->whereHas('user', function ($query) use($value){
                $query->where('department', $value);
            })->get();
        }




        return view('delivery.index', compact('deliveries'));
    }
    public function complete($id){
        $delivery = OutstandingDelivery::where('id', $id)->first();
        $delivery->c_date = Carbon::now('GMT+1');
        $delivery->reciving_person = Auth::user()->id;
        $delivery->save();
        return back()->with(['success' => 'Order Complete']);
    }
    public function partial($id){
        $delivery = OutstandingDelivery::where('id', $id)->first();
        $partial = new Partial();
        $partial->date = Carbon::now('GMT+1');
        $partial->person = Auth::user()->fullname;
        $partial->delivery_id = $delivery->id;
        $partial->save();
        $delivery->p_date = Carbon::now('GMT+1');
        $delivery->reciving_person = Auth::user()->id;
        $delivery->save();
        return back()->with(['success' => 'Order Partial']);
    }
}
