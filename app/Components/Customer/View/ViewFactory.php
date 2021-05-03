<?php

declare(strict_types=1);

namespace App\Components\Customer\View;

interface ViewFactory
{
    public function create(bool $showArchived = false): View;
}
