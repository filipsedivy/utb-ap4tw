<?php

declare(strict_types=1);

namespace App\Components\Profile\ChangePersonalData;

interface ChangePersonalDataFactory
{
    public function create(): ChangePersonalData;
}
