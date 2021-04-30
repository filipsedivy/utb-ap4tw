<?php

declare(strict_types=1);

namespace App\Components\ViewNote;

use App\Database\Entity\Note;

interface ViewNoteFactory
{
    public function create(Note $note): ViewNote;
}
