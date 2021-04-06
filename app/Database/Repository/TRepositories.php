<?php declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;
use App\Database\Entity\Note;

trait TRepositories
{
    final public function getEmployeeRepository(): EmployeeRepository
    {
        $repo = $this->getRepository(Employee::class);
        assert($repo instanceof EmployeeRepository);
        return $repo;
    }

    final public function getNoteRepository(): NoteRepository
    {
        $repo = $this->getRepository(Note::class);
        assert($repo instanceof NoteRepository);
        return $repo;
    }
}