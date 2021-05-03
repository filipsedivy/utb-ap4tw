<?php

declare(strict_types=1);

namespace App\Events\Note;

final class UpdateNoteEvent
{
    private int $id;

    private ?string $note;

    private ?bool $visibility;

    public function __construct(int $id, ?string $note = null, ?bool $visibility = null)
    {
        $this->id = $id;
        $this->note = $note;
        $this->visibility = $visibility;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }
}
