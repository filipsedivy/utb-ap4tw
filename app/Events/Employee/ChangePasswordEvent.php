<?php declare(strict_types=1);

namespace App\Events\Employee;

final class ChangePasswordEvent
{
    private int $user;

    private string $password;

    public function __construct(int $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}