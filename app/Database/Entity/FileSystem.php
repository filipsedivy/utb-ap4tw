<?php declare(strict_types=1);

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils;

/**
 * @ORM\Entity(repositoryClass="App\Database\Repository\FileSystemRepository")
 * @ORM\Table(name="file_system")
 * @property-read int $size
 * @property-read string $path
 * @property-read string $contentType
 * @property-read Employee $user
 * @property-read Utils\DateTime $createdAt;
 */
class FileSystem extends BaseEntity
{
    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private int $size;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private string $path;

    /**
     * @ORM\Column(type="string", options={"default": "application/octet-stream"})
     */
    private string $contentType = 'application/octet-stream';

    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id")
    */
    private Employee $user;

    /**
     * @ORM\Column(type="datetime")
    */
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getUser(): Employee
    {
        return $this->user;
    }

    public function setUser(Employee $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): Utils\DateTime
    {
        return Utils\DateTime::from($this->createdAt);
    }
}