<?php declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Database\Repository\EmployeeRepository")
 * @ORM\Table(name="employee")
 * @property-read string $username
 * @property-read string $email
 * @property-read bool $active
 * @property-read ?string $authToken
 */
class Employee extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $authToken;

    public function __construct()
    {
        $this->active = true;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @param string|null $authToken
     */
    public function setAuthToken(?string $authToken): void
    {
        $this->authToken = $authToken;
    }
}