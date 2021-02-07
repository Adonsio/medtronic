<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FileController;
use App\Models\Setup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SummaryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $setup = Setup::first();

    return view('dashboard', compact('setup'));
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/setup',
    [SetupController::class, 'index']
    )->name('setup')->middleware(['auth']);

Route::group(['prefix' => 'import', 'middleware' => 'auth'], function (){
    Route::get('users/{file}', [ImportController::class, 'users']);
    Route::get('orgas/{file}', [ImportController::class, 'orgas']);
    Route::get('products/{file}', [ImportController::class, 'product']);
    Route::get('supplier/{file}', [ImportController::class, 'supplier']);
});

Route::group(['prefix' => 'list', 'middleware' => 'auth'], function (){
    Route::get('users', [ListController::class, 'user'])->name('user-list');
    Route::get('organisations', [ListController::class, 'organisations'])->name('orga-list');
    Route::get('products', [ListController::class, 'products'])->name('product-list');
    Route::get('suppliers', [ListController::class, 'suppliers'])->name('supplier-list');
    Route::get('bulk-order', [ListController::class, 'bulkorder'])->name('bulk-list');

});

Route::group(['prefix' => 'order', 'middleware' => 'auth'], function (){
   Route::get('/', [OrderController::class, 'index'])->name('order');
   Route::get('/individual', [OrderController::class, 'individual'])->name('individual-order');
});

Route::group(['prefix' => 'summary', 'middleware' => 'auth'], function (){
    Route::get('/bulk', [SummaryController::class, 'index'])->name('bulk-summary');
    Route::get('/individual', [SummaryController::class, 'individual'])->name('individual-summary');
    Route::get('analyse', [SummaryController::class, 'analyse'])->name('analyse');
});

Route::get('/home', function (){
    return view('overview.index');
})->middleware(['auth'])->name('home');

Route::post('/file/upload', [FileController::class, 'upload'])->name('upload')->middleware(['auth']);
Route::get('/file/upload', [FileController::class, 'index'])->name('upload.index')->middleware(['auth']);

Route::group(['prefix' => 'coupon', 'middleware' => 'auth'], function (){
    Route::get('/bulk', [CouponController::class, 'bulk']);
});

Route::group(['prefix' => 'coupon', 'middleware' => 'auth'], function () {
    Route::get('individual/create/{identifier}', [CouponController::class, 'createIndividual'])->name('individual-coupon');
    Route::get('bulk/create', [CouponController::class, 'createBulk'])->name('bulk-coupon');
    Route::get('individual/print/{identifier}', [CouponController::class, 'printIndivudual'])->name('print-individual');
    Route::post('bulk/print/{identifier}', [CouponController::class, 'printBulk'])->name('print-bulk');
});

Route::group(['prefix' => 'delivery', 'middleware' => 'auth'], function (){
    Route::get('/', [DeliveryController::class, 'index'])->name('outstanding');
    Route::get('/complete/{id}', [DeliveryController::class, 'complete'])->name('complete');
    Route::get('/partial/{id}', [DeliveryController::class, 'partial'])->name('partial');
});

Route::post('/user/role/{id}', [ListController::class, 'changeRole'])->middleware(['auth']);
Route::get('/user/reset/{id}', [ListController::class, 'resetPassword'])->middleware(['auth']);
Route::get('/user/edit/{id}', [ListController::class, 'editUser'])->middleware(['auth']);
Route::post('/user/edit/{id}', [ListController::class, 'updateUser'])->middleware(['auth']);
Route::get('/download/{id}', [FileController::class, 'download'])->middleware(['auth']);
Route::get('/invoices', [DeliveryController::class, 'invoice'])->middleware(['auth']);
Route::get('/chart', [SummaryController::class, 'chart'])->middleware(['auth']);

Route::get('/meta', [ListController::class, 'meta'])->name('meta')->middleware(['auth']);
Route::get('/invoice/list', [DeliveryController::class, 'invoicelist'])->name('invoicelist')->middleware(['auth']);