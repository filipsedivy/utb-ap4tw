<?php

declare(strict_types=1);

namespace App\Components\Employee\Form;

interface FormFactory
{
    public function create(): Form;
}
