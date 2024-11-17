<?php

use App\Http\Controllers\NoteController;
use App\Models\QueryRepositories\NoteRepository;
use Illuminate\Support\Facades\Route;


// Route::get('/test', function () {
//     return response()->json(['message' => 'API is working!']);
// });

// Route::get('/', function () {
//     return response()->json(['message' => 'Root is working!']);
// });

// Route::get('/test-notes/{date}', [NoteRepository::class, 'getNotes']);

Route::get('/week-notes', [NoteController::class, 'getWeekNotes']);
Route::post('/add-note', [NoteController::class, 'addNote']);
Route::get('/get-note', [NoteController::class, 'getNote']);
Route::delete('/delete-note', [NoteController::class, 'deleteNote']);


Route::post('/send-daily-note-email', [NoteController::class, 'triggerEmailCheck']);
