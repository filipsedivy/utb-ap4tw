<?php

declare(strict_types=1);

namespace App\Components\Profile\ChangePassword;

interface ChangePasswordFactory
{
    public function create(): ChangePassword;
}
