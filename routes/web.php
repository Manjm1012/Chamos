<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'es'], true), 404);

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return redirect()->back();
})->name('locale.switch');
