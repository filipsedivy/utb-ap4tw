<?php declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;
use App\Database\Entity\Note;

trait TRepositories
{
    final public function getEmployeeRepository(): EmployeeRepository
    {
        return $this->getRepository(Employee::class);
    }

    final public function getNoteRepository(): NoteRepository
    {
        return $this->getRepository(Note::class);
    }
}