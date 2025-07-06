<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroceryItemController;
use App\Http\Controllers\GroceryListController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Events\TestMessageSent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;


Route::middleware(['auth:sanctum'])->post('/send-daily-note-email', [NoteController::class, 'triggerEmailCheck']);






// Routes for UserController (authentication related)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/password-reset', [PasswordResetLinkController::class, 'store']);
Route::post('/change-password', [NewPasswordController::class, 'store']);

// Protected routes
// User
Route::middleware(['auth:sanctum'])->get('/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

//Customer service chat
Route::middleware('auth:sanctum')->post('/send-message', [ChatController::class, 'sendMessage']);

Route::middleware(['auth:sanctum'])->post('/broadcasting/auth', function (Request $request) {
    Log::info('Custom broadcasting auth', [
        'user' => $request->user(),
        'has_auth_header' => $request->hasHeader('Authorization'),
        'bearer_token' => $request->bearerToken() ? 'present' : 'missing',
        'channel_name' => $request->input('channel_name'),
        'socket_id' => $request->input('socket_id'),
    ]);

    try {
        $response = Broadcast::auth($request);
        Log::info('Broadcasting auth success', ['response' => $response]);
        return $response;
    } catch (\Exception $e) {
        Log::error('Broadcasting auth failed', ['error' => $e->getMessage()]);
        throw $e;
    }
});

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
    Route::delete('/delete-list/{listId}', [GroceryListController::class, 'deleteGroceryList']);
});

// Routes for GroceryItemController
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/save-items', [GroceryItemController::class, 'saveItems']);
    Route::get('/grocery-items', [GroceryItemController::class, 'getItemsForGroceryList']);
    Route::delete('/delete-item/{itemId}', [GroceryItemController::class, 'deleteItem']);
});
