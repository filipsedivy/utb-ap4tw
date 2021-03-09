<?php declare(strict_types=1);

namespace App\Components\ForgotPassword;

interface ForgotPasswordFactory
{
    public function create(): ForgotPassword;
}