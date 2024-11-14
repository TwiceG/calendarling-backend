<?php

namespace App\Http\Controllers;

use App\Models\QueryRepositories\NoteRepository;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    protected $noteRepository;
    protected $emailService;

    public function __construct(NoteRepository $noteRepository, EmailService $emailService)
    {
        $this->noteRepository = $noteRepository;
        $this->emailService = $emailService;
    }

    public function getWeekNotes(Request $request)
    {
        $date = $request->query('date');
        return $this->noteRepository->getNotes($date);
    }

    public function addNote(Request $request)
    {
        $date = $request->json('date');
        $note = $request->json('note');

        $result = $this->noteRepository->addNote($note, $date);

        return response()->json(['success' => $result]);
    }

    public function getNote(Request $request): string
    {
        $date = $request->query('date');
        return $this->noteRepository->getNote($date);
    }

    public function triggerEmailCheck(Request $request)
    {
        $date = now()->format('Y-m-d');
        $note = $this->noteRepository->getNote($date);
        $userEmail = env('EMAIL_ADDRESS');

        Log::info("Note for today: " . $note);


        // Check if note exists (not empty) before attempting to send an email
        if (empty($note)) {
            return response()->json(['message' => 'No note available for today. Email not sent.'], Response::HTTP_OK);
        }

        // Send the email if the note exists
        if ($this->emailService->sendNoteEmail($note, $date, $userEmail)) {
            return response()->json(['message' => 'Email sent successfully.'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Failed to send email. Controller'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
