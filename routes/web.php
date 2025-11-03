<?php

use App\Http\Controllers\TestReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/swagger.yaml', function () {
    return response()->file(base_path('docs/swagger.yaml'), [
        'Content-Type' => 'application/yaml',
    ]);
});

// Test Reports Routes
Route::prefix('test-reports')->name('test-reports.')->group(function () {
    Route::get('/', [TestReportController::class, 'index'])->name('index');
    Route::get('coverage/{path?}', [TestReportController::class, 'coverage'])
        ->where('path', '.*')
        ->name('coverage');
    Route::get('testdox', [TestReportController::class, 'testdox'])->name('testdox');
});