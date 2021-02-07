<?php

use App\Http\Controllers\DeliveryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Supplier;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
if (env('APP_ENV') !== 'local'){
    URL::forceScheme('https');
}
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/user/{id}', function ($id){
    $user = User::where('id', $id)->get();
    return response()->json(['user' => $user]);
});
Route::get('/users', function (){
    $users = User::all();
    return response()->json(['users' => $users]);
});
Route::get('suppliers', function (){
   $suppliers = Supplier::with('products')->get();
   return response()->json(['suppliers' => $suppliers]);
});

Route::post('/addbulk', [OrderController::class, 'createOrder'] );
Route::post('/addIndividual', [OrderController::class, 'createOrder'] );
Route::get('/orders/{id}/{type}', [OrderController::class, 'getOrders']);
Route::get('/orders/individual/{id}', [OrderController::class, 'getIndividualOrders']);

Route::get('/summaries', [SummaryController::class, 'getSummaries']);

Route::patch('/update/order', [OrderController::class, 'updateOrder']);
Route::patch('/update/order/individual/', [OrderController::class, 'updateIndividualOrder']);
Route::delete('/delete/order/{id}', [OrderController::class, 'deleteOrder']);
Route::delete('/delete/order/individual/{id}', [OrderController::class, 'deleteIndividualOrder']);

Route::get('/getSites', [OrderController::class, 'getSites']);
Route::get('/getDepartments', [OrderController::class, 'getDepartments']);
Route::get('/getGroups', [OrderController::class, 'getGroups']);
Route::get('/invoices/', [DeliveryController::class, 'getInvoices']);
Route::post('/pendinginvoice', [DeliveryController::class, 'setPending']);
Route::get('/getPending', [DeliveryController::class, 'getPending']);

Route::get('/products/{id}/{fullname}', [OrderController::class, 'getProducts']);

Route::get('/getX', [SummaryController::class, 'getX']);
Route::get('/datasets', [SummaryController::class, 'datasets']);
Route::get('/grouptotal', [SummaryController::class, 'groupTotal']);
Route::get('/clarensvalue', [SummaryController::class, 'clarensValue']);
Route::get('/clarenstotal', [SummaryController::class, 'clarensTotal']);
Route::get('/saxonvalue', [SummaryController::class, 'saxonValue']);
Route::get('/saxontotal', [SummaryController::class, 'saxonTotal']);

Route::post('complete/invoice/{id}', [DeliveryController::class, 'completeInvoice']);