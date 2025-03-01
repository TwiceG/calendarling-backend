<?php

namespace App\Models\QueryRepositories;

use Illuminate\Support\Facades\DB;
use App\Models\Note;
use Illuminate\Support\Facades\Log;


class NoteRepository
{
    public function getNotes($date, $userId): array
    {
        $dates = self::getDates($date);

        // Fetch all notes for the week for the user
        $notes = DB::table('notes')
            ->whereIn('date', $dates)
            ->where('user_id', $userId)
            ->pluck('note', 'date')
            ->toArray();

        // Initialize the result array for the week
        $result = [];
        foreach ($dates as $date) {
            $result[$date] = $notes[$date] ?? '';
        }

        return array_values($result);
    }


    public function addNote($note, $date, $userId)
    {
        return Note::updateOrCreate(
            ['date' => $date, 'user_id' => $userId],
            ['note' => $note]
        );
    }

    public function getTodayNotes()
    {
        $date = now()->format('Y-m-d');

        return DB::table('notes')
            ->where('date', $date)
            ->get();
    }



    public function getNote($date, $userId)
    {
        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);
        $formattedDate = $dateObject ? $dateObject->format('Y-m-d') : $date;

        $note = DB::table('notes')
            ->where('date', $formattedDate)
            ->where('user_id', $userId)
            ->value('note');

        // $note = DB::table('notes')->where('date', $formattedDate)->first(); if need the full data not just the note

        return $note ?? '';
    }

    public function deleteNote($date, $userId)
    {
        return Note::where('date', $date)
            ->where('user_id', $userId)
            ->delete();
    }

    public function getDates($date): array
    {
        // Convert the input date string to a DateTime object
        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);

        // If the date format is invalid, return an empty array
        if (!$dateObject) {
            return [];
        }

        // Clone the date and set it to the start of the week (Monday)
        $startOfWeek = clone $dateObject;
        $startOfWeek->modify('this week')->modify('monday');

        // Clone the start date to get to the end of the week (Sunday)
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');

        // Generate an array with all dates from Monday to Sunday
        $dates = [];
        while ($startOfWeek <= $endOfWeek) {
            $dates[] = $startOfWeek->format('Y-m-d');
            $startOfWeek->modify('+1 day');
        }

        return $dates;
    }
}
