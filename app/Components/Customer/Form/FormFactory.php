<?php

declare(strict_types=1);

namespace App\Components\Customer\Form;

interface FormFactory
{
    public function create(?int $customerId = null): Form;
}
