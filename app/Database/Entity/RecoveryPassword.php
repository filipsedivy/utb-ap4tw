<?php

declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils;

/**
 * @ORM\Entity(repositoryClass="App\Database\Repository\RecoveryPasswordRepository")
 * @ORM\Table(name="recovery_password")
 * @property-read Employee $user
 * @property-read string $token
 * @property-read Utils\DateTime $createdAt
 * @property-read Utils\DateTime $expiredAt
 */
class RecoveryPassword extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="user_id")
     */
    private Employee $user;

    /**
     * @ORM\Column(type="string")
     */
    private string $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $expiredAt;

    public function __construct()
    {
        $this->createdAt = new Utils\DateTime('now');
    }

    public function getUser(): Employee
    {
        return $this->user;
    }

    public function setUser(Employee $user): void
    {
        $this->user = $user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getCreatedAt(): Utils\DateTime
    {
        return Utils\DateTime::from($this->createdAt);
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getExpiredAt(): Utils\DateTime
    {
        return Utils\DateTime::from($this->expiredAt);
    }

    public function setExpiredAt(\DateTime $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }
}
