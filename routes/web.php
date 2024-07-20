<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\EmailTemplateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', function () {
    if (Auth::check()) {
        # code...
    } else {
        return redirect()->route('login');
    }
});

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/general-update', 'generalUpdate')->name('general.update');
        Route::post('/pass-update', 'passUpdate')->name('pass.update');
    });


    Route::prefix('email-lists')->name('email.lists.')->controller(EmailListController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::get('/fetch', 'fetch')->name('fetch');
        Route::post('/save', 'save')->name('save');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('subscribers')->name('subscribers.')->controller(SubscriberController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::get('/fetch', 'fetch')->name('fetch');
        Route::post('/save', 'save')->name('save');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::post('/bulk', 'bulk')->name('bulk');
    });

    Route::prefix('email-templates')->name('email.templates.')->controller(EmailTemplateController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::get('/fetch', 'fetch')->name('fetch');
        Route::post('/save', 'save')->name('save');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('campaigns')->name('campaigns.')->controller(CampaignController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::get('/fetch', 'fetch')->name('fetch');
        Route::post('/save', 'save')->name('save');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/send/{id}', 'send')->name('send');
        Route::post('/schedule/{id}', 'schedule')->name('schedule');
    });
});
