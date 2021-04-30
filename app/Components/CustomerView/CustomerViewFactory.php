<?php

declare(strict_types=1);

namespace App\Components\CustomerView;

interface CustomerViewFactory
{
    public function create(bool $showArchived = false): CustomerView;
}
