<?php

declare(strict_types=1);

namespace App\Components\Dashboard\UI\Total;

interface TotalFactory
{
    public function create(string $classObject): Total;
}
