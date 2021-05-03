<?php

declare(strict_types=1);

namespace App\Components\Employee\View;

interface ViewFactory
{
    public function create(): View;
}
