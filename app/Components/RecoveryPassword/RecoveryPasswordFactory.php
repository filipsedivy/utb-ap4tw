<?php declare(strict_types=1);

namespace App\Components\RecoveryPassword;

use App\Database\Entity;

interface RecoveryPasswordFactory
{
    public function create(Entity\RecoveryPassword $recoveryPassword): RecoveryPassword;
}