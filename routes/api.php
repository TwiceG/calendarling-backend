<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroceryItemController;
use App\Http\Controllers\GroceryListController;


Route::middleware(['auth:sanctum'])->post('/send-daily-note-email', [NoteController::class, 'triggerEmailCheck']);


// Routes for UserController (authentication related)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes
// User
Route::middleware(['auth:sanctum'])->get('/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

// Routes for NoteController
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/week-notes', [NoteController::class, 'getWeekNotes']);
    Route::post('/add-note', [NoteController::class, 'addNote']);
    Route::get('/get-note', [NoteController::class, 'getNote']);
    Route::delete('/delete-note', [NoteController::class, 'deleteNote']);
});

// Routes for GroceryListController
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add-grocery-list', [GroceryListController::class, 'addGroceryList']);
    Route::get('/grocery-lists', [GroceryListController::class, 'getGroceryLists']);
    Route::get('/grocery-list', [GroceryListController::class, 'getGroceryListById']);
    Route::delete('/delete-list', [GroceryListController::class, 'deleteGroceryList']);
});

// Routes for GroceryItemController
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/save-items', [GroceryItemController::class, 'saveItems']);
    Route::get('/grocery-items', [GroceryItemController::class, 'getItemsForGroceryList']);
    Route::delete('/grocery-item/{id}', [GroceryItemController::class, 'deleteGroceryItem']);
});
