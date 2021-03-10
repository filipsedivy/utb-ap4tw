<?php declare(strict_types=1);

namespace App\Events\Note;

final class DeleteNoteEvent
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}