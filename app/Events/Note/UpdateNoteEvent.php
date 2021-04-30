<?php

declare(strict_types=1);

namespace App\Events\Note;

final class UpdateNoteEvent
{
    private int $id;

    private string $note;

    public function __construct(int $id, string $note)
    {
        $this->id = $id;
        $this->note = $note;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): string
    {
        return $this->note;
    }
}
