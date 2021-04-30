<?php

declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_type")
 */
class ContactType
{
    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     * @ORM\Id()
     */
    private string $key;

    /**
     * @ORM\Column(type="string")
     */
    private string $display;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getDisplay(): string
    {
        return $this->display;
    }

    public function setDisplay(string $display): void
    {
        $this->display = $display;
    }
}
