<?php

declare(strict_types=1);

namespace App\Events\Customer;

final class EditCustomerEvent
{
    private int $customerId;

    private ?string $name;

    private ?bool $archive;

    public function __construct(int $customerId, ?string $name = null, ?bool $archive = null)
    {
        $this->customerId = $customerId;
        $this->name = $name;
        $this->archive = $archive;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }
}
