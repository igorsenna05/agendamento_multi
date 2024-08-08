<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DecisionTreeController;
use App\Http\Controllers\ScheduleSlotController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Show Decision Tree at the root.
Route::get('/', [DecisionTreeController::class, 'user'])->name('decision_tree.user');
Route::get('/decision_tree/handle/{id}', [DecisionTreeController::class, 'handleMenuClick'])->name('decision_tree.handleMenuClick');
Route::get('/decision_tree/perform/{id}', [DecisionTreeController::class, 'performAction'])->name('decision_tree.performAction');


// Scheduling
Route::get('/scheduling', [SchedulingController::class, 'index'])->name('scheduling.index');
Route::post('/scheduling', [SchedulingController::class, 'store'])->name('scheduling.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //services
    Route::resource('services', ServiceController::class);
    //locations
    Route::resource('locations', LocationController::class);
    //decision tree
    Route::resource('decision_tree', DecisionTreeController::class);
    //schedule slots
    Route::get('schedule_slots/bulk_create', [ScheduleSlotController::class, 'bulkCreate'])->name('schedule_slots.bulkCreate');
    Route::post('schedule_slots/bulk_store', [ScheduleSlotController::class, 'bulkStore'])->name('schedule_slots.bulkStore');
    Route::get('schedule_slots/bulk_destroy', [ScheduleSlotController::class, 'bulkDestroyPrep'])->name('schedule_slots.bulkDestroyPrep');
    Route::delete('schedule_slots/bulk_destroy', [ScheduleSlotController::class, 'bulkDestroy'])->name('schedule_slots.bulkDestroy');
    Route::resource('schedule_slots', ScheduleSlotController::class);
    //instructions
    Route::resource('instructions', InstructionController::class);
    //profile
    Route::resource('roles', RoleController::class);
});





require __DIR__.'/auth.php';
