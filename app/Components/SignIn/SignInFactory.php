<?php

declare(strict_types=1);

namespace App\Components\SignIn;

interface SignInFactory
{
    public function create(): SignIn;
}
