<?php

declare(strict_types=1);

namespace App\Components\Employee\Form;

use App\Database\Entity\Employee;

interface FormFactory
{
    public function create(?Employee $employee = null): Form;
}
