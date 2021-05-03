<?php

declare(strict_types=1);

namespace App\Components\Note\View;

use App\Database\Entity;

interface ViewFactory
{
    public function create(Entity\Note $note): View;
}
