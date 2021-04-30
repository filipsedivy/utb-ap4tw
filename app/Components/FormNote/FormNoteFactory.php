<?php

declare(strict_types=1);

namespace App\Components\FormNote;

interface FormNoteFactory
{
    public function create(?int $id = null): FormNote;
}
