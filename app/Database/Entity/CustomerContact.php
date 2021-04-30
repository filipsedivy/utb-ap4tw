<?php

declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_contact")
 */
class CustomerContact extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="ContactType")
     * @ORM\JoinColumn(name="type_key", referencedColumnName="key")
     */
    private ContactType $type;

    /**
     * @ORM\Column(type="string")
     */
    private string $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active;

    public function __construct()
    {
        $this->active = true;
    }

    public function getType(): ContactType
    {
        return $this->type;
    }

    public function setType(ContactType $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
