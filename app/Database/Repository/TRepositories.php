<?php

declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\Customer;
use App\Database\Entity\Employee;
use App\Database\Entity\FileSystem;
use App\Database\Entity\Note;
use App\Database\Entity\RecoveryPassword;

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

    final public function getCustomerRepository(): CustomerRepository
    {
        $repo = $this->getRepository(Customer::class);
        assert($repo instanceof CustomerRepository);
        return $repo;
    }

    final public function getFileSystemRepository(): FileSystemRepository
    {
        $repo = $this->getRepository(FileSystem::class);
        assert($repo instanceof FileSystemRepository);
        return $repo;
    }

    final public function getRecoveryPasswordRepository(): RecoveryPasswordRepository
    {
        $repo = $this->getRepository(RecoveryPassword::class);
        assert($repo instanceof RecoveryPasswordRepository);
        return $repo;
    }
}
