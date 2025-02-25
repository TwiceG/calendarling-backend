<?php

namespace App\Http\Controllers;

use App\Models\QueryRepositories\NoteRepository;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    protected $noteRepository;
    protected $emailService;
    private $userId;

    public function __construct(NoteRepository $noteRepository, EmailService $emailService)
    {
        $this->noteRepository = $noteRepository;
        $this->emailService = $emailService;
        // Set the user ID when the user is logged in
        $this->userId = Auth::user()->id;
    }

    public function getWeekNotes(Request $request)
    {
        $date = $request->query('date');
        return $this->noteRepository->getNotes($date, $this->userId);
    }

    public function addNote(Request $request)
    {

        $date = $request->json('date');
        $note = $request->json('note');

        $result = $this->noteRepository->addNote($note, $date, $this->userId);

        return response()->json(["Successfully added your note: $note. :)" => $result]);
    }

    public function getNote(Request $request): string
    {
        $date = $request->query('date');
        return $this->noteRepository->getNote($date, $this->userId);
    }

    public function deleteNote(Request $request): string
    {
        $date = $request->json('date');
        $result = $this->noteRepository->deleteNote($date, $this->userId);
        if (!$result) {
            return response()->json(['message' => "Failed to delete note for $date or no note found"], Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => "Successfully deleted note for $date"], Response::HTTP_OK);
    }


    public function triggerEmailCheck(Request $request)
    {
        $date = now()->format('Y-m-d');
        $note = $this->noteRepository->getNote($date, $this->userId);
        $userEmail = env('EMAIL_ADDRESS');

        Log::info("Note for today: " . $note);


        // Check if note exists (not empty) before attempting to send an email
        if (empty($note)) {
            return response()->json(['message' => 'No note available for today. Email not sent.'], Response::HTTP_OK);
        }

        // Send the email if the note exists
        if ($this->emailService->sendNoteEmail($note, $date, $userEmail)) {
            return response()->json(['message' => `Email sent successfully to $userEmail`], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Failed to send email. Controller'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
