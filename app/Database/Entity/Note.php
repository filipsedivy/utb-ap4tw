<?php declare(strict_types=1);

namespace App\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Database\Repository\NoteRepository")
 * @ORM\Table(name="note")
 */
class Note extends BaseEntity
{
    /**
     * @ORM\Column(type="text")
     */
    private string $note;

    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private Employee $creator;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $private;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $edited;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->edited = null;
        $this->private = false;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getCreator(): Employee
    {
        return $this->creator;
    }

    public function setCreator(Employee $creator): void
    {
        $this->creator = $creator;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    public function getEdited(): ?DateTime
    {
        return $this->edited;
    }

    public function setEdited(?DateTime $edited): void
    {
        $this->edited = $edited;
    }
}