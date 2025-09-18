<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandardCalculatorController;
use App\Http\Controllers\ScientificCalculatorController;
use App\Http\Controllers\ProgrammerCalculatorController;

Route::get('/', function () {
    return redirect()->route('calculator.standard');
});

// Standard Calculator
Route::match(['get','post'], '/calculator/standard', [StandardCalculatorController::class, 'handle'])
    ->name('calculator.standard');

// Scientific Calculator
Route::match(['get','post'], '/calculator/scientific', [ScientificCalculatorController::class, 'handle'])
    ->name('calculator.scientific');

// Programmer Calculator
Route::match(['get','post'], '/calculator/programmer', [ProgrammerCalculatorController::class, 'handle'])
    ->name('calculator.programmer');
