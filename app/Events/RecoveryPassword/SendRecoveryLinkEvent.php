<?php

declare(strict_types=1);

namespace App\Events\RecoveryPassword;

use App\Database\Entity\Employee;
use Nette;

/** @property-read Employee $employee */
final class SendRecoveryLinkEvent
{
    use Nette\SmartObject;

    private Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
