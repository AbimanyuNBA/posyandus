<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kader\DashboardController as KaderDashboard;
use App\Http\Controllers\Kader\BalitaController;
use App\Http\Controllers\Kader\PengukuranController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PosyanduController;
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', fn() => view('welcome'));

// ── Kader ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:kader'])
    ->prefix('kader')
    ->name('kader.')
    ->group(function () {

    Route::get('/dashboard', [KaderDashboard::class, 'index'])
        ->name('dashboard');

    // Balita CRUD
    Route::resource('balita', BalitaController::class)
        ->parameters(['balita' => 'balita']);

    // KMS — harus sebelum resource nested agar tidak bentrok
    Route::get('balita/{balita}/kms', [BalitaController::class, 'kms'])
        ->name('balita.kms');

    // Pengukuran — nested di bawah balita, shallow
    // Menghasilkan:
    //   GET  kader/balita/{balita}/pengukuran/create → kader.balita.pengukuran.create
    //   POST kader/balita/{balita}/pengukuran        → kader.balita.pengukuran.store
    //   DEL  kader/pengukuran/{pengukuran}           → kader.pengukuran.destroy
    Route::resource('balita.pengukuran', PengukuranController::class)
        ->shallow()
        ->only(['create', 'store', 'destroy'])
        ->parameters(['balita' => 'balita', 'pengukuran' => 'pengukuran']);
});

// ── Admin ───────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

Route::get('/dashboard', [AdminDashboard::class, 'index'])
    ->name('dashboard');



    Route::resource('posyandu', PosyanduController::class);
    Route::resource('users', UserController::class);

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.pdf');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])
        ->name('laporan.excel');
});

require __DIR__.'/auth.php';