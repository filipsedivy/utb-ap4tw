<?php declare(strict_types=1);

namespace App\Events\Employee;

final class ChangePersonalDataEvent
{
    private int $user;

    private string $name;

    public function __construct(int $user, string $name)
    {
        $this->user = $user;
        $this->name = $name;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function getName(): string
    {
        return $this->name;
    }
}