<?php

declare(strict_types=1);

namespace App\Components\FormCustomer;

interface FormCustomerFactory
{
    public function create(?int $customerId = null): FormCustomer;
}
