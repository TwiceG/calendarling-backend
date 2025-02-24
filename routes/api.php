<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;

// Routes for NoteController
Route::get('/week-notes', [NoteController::class, 'getWeekNotes']);
Route::post('/add-note', [NoteController::class, 'addNote']);
Route::get('/get-note', [NoteController::class, 'getNote']);
Route::delete('/delete-note', [NoteController::class, 'deleteNote']);
Route::post('/send-daily-note-email', [NoteController::class, 'triggerEmailCheck']);

// Routes for UserController (authentication related)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->get('/me', [UserController::class, 'me']);
Route::middleware(['auth:sanctum'])->post('/logout', [UserController::class, 'logout']);
