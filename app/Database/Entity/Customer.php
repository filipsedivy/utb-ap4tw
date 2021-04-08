<?php declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Database\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 */
class Customer extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $archived;

    /**
     * @ORM\ManyToMany(targetEntity="CustomerContact")
     * @ORM\JoinTable(name="customer_contacts",
     *     joinColumns={@ORM\JoinColumn(name="customer_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")})
     */
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->archive = false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): void
    {
        $this->archived = $archived;
    }
}