<?php declare(strict_types=1);

namespace App\Events\Note;

use App\Database\Entity\Employee;

final class AddNoteEvent
{
    private string $note;

    private ?Employee $employee;

    public function __construct(string $note, ?Employee $employee = null)
    {
        $this->note = $note;
        $this->employee = $employee;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }
}