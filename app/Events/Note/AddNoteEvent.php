<?php

declare(strict_types=1);

namespace App\Events\Note;

use App\Database\Entity\Employee;

final class AddNoteEvent
{
    private string $note;

    private ?Employee $employee;

    private ?bool $visibility;

    public function __construct(
        string $note,
        ?bool $visibility = null,
        ?Employee $employee = null
    ) {
        $this->note = $note;
        $this->employee = $employee;
        $this->visibility = $visibility;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }
}
