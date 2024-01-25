<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

Route::get('/products', [ProductController::class, 'showProducts'])->name('showProduct');

Route::post('/signInAuth', [AdminController::class, 'signInAuth'])->name('signInAuth');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
Route::post('/change-password', [AdminController::class, 'changePassword'])->name('changePassword');
Route::view('/', 'dashboards.index')->middleware('admin.auth');
Route::view('/sign-in', 'pages.sign-in')->name('signInPage');

Route::view('/add-product-form', 'pages.add-product');
Route::post('/add-product', [ProductController::class, 'addProduct'])->name('addProduct');

Route::post('/add-user', [UserController::class, 'addUser'])->name('addUser');
Route::get('/add-user-form/{email?}', [UserController::class, 'showAddUserForm'])->name('addUserForm');
Route::post('/update-user', [UserController::class, 'updateUser'])->name('updateUser');
Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('deleteUser');
Route::get('/users', [UserController::class, 'showUsers'])->name('showUsers');


Route::post('/add-customer', [CustomerController::class, 'addCustomer'])->name('addCustomer');
Route::get('/add-customer-form/{cnic?}', [CustomerController::class, 'showCustomerDetailsForm'])->name('customerDetailsForm');
Route::post('/update-customer', [CustomerController::class, 'updateCustomer'])->name('updateCustomer');
Route::post('/delete-customer', [CustomerController::class, 'deleteCustomer'])->name('deleteCustomer');
Route::get('/customer', [CustomerController::class, 'showCustomers'])->name('showCustomers');

Route::get('/change-password-form', function () {
    return view('pages.change-password');
});

Route::get('/test', function () {
    return view('pages.add-project');
});