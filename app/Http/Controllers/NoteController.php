<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\QueryRepositories\NoteRepository;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

class NoteController extends Controller
{
    protected $noteRepository;
    protected $emailService;
    protected $userController;
    private $userId;

    public function __construct(NoteRepository $noteRepository, EmailService $emailService, UserController $userController)
    {
        $this->userController = $userController;
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

    private function  getNotes(): Collection
    {
        return $this->noteRepository->getTodayNotes();
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


    public function triggerEmailCheck()
    {
        $date = now()->format('Y-m-d');
        $notes = $this->getNotes();
        Log::info("Notes for today: " . $notes);

        $sentEmails = [];
        $failedEmails = [];
        foreach ($notes as $note) {
            $userId = $note->user_id;
            $user = $this->userController->getUser($userId);
            $userName = $user->name;
            $userEmail = $this->userController->getUserEmail($userId);
            if ($this->emailService->sendNoteEmail($note->note, $date, $userEmail, $userName)) {
                $sentEmails = $userEmail;
            } else {
                $failedEmails = $userEmail;
            }
        }

        if (!$failedEmails) {
            return response()->json([
                'message' => "All emails sent successfully",
                'sent_emails' => $sentEmails
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => "Some emails failed to send",
                'sent_emails' => $sentEmails,
                'failed_emails' => $failedEmails
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
