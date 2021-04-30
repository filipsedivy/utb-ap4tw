<?php

declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette;

/**
 * @property-read int $id
*/
abstract class BaseEntity
{
    use Nette\SmartObject;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
