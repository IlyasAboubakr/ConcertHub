<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\PublicEventController::class, 'index'])->name('home');
Route::get('/events', [\App\Http\Controllers\PublicEventController::class, 'allEvents'])->name('events.index');
Route::get('/events/{event}', [\App\Http\Controllers\PublicEventController::class, 'show'])->name('events.show');
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    if (in_array(auth()->user()->role, ['admin', 'administrator'])) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'organizer') {
        return redirect()->route('organizer.dashboard');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/organizers/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'organizersStats'])->name('organizers.stats');
    Route::get('/export-tickets', [\App\Http\Controllers\Admin\DashboardController::class, 'exportTickets'])->name('export.tickets');
    Route::post('/users/{id}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
});

Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', \App\Http\Controllers\Organizer\EventController::class);
    Route::get('events/{event}/export', [\App\Http\Controllers\Organizer\EventController::class, 'exportAudit'])->name('events.export');
    
    // Ticket Types routes nested under events
    Route::get('events/{event}/tickets/create', [\App\Http\Controllers\Organizer\TicketTypeController::class, 'create'])->name('tickets.create');
    Route::post('events/{event}/tickets', [\App\Http\Controllers\Organizer\TicketTypeController::class, 'store'])->name('tickets.store');
    Route::get('events/{event}/tickets/{ticketType}/edit', [\App\Http\Controllers\Organizer\TicketTypeController::class, 'edit'])->name('tickets.edit');
    Route::put('events/{event}/tickets/{ticketType}', [\App\Http\Controllers\Organizer\TicketTypeController::class, 'update'])->name('tickets.update');
    Route::delete('events/{event}/tickets/{ticketType}', [\App\Http\Controllers\Organizer\TicketTypeController::class, 'destroy'])->name('tickets.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Client Routes
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
});

// Checkout & Ticket Routes (Require Auth, mostly Client but auth generally)
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/tickets', [\App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/events/{event}/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment', [\App\Http\Controllers\CheckoutController::class, 'showPayment'])->name('checkout.payment');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/tickets/{orderItem}/download', [\App\Http\Controllers\CheckoutController::class, 'downloadTicket'])->name('checkout.download');
});

require __DIR__.'/auth.php';
