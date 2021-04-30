<?php declare(strict_types=1);

namespace App\Components\FileSystem;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Repository\EmployeeRepository;
use App\Database\Repository\FileSystemRepository;
use Nette\Security\User;

final class FileSystem extends CoreControl
{
    private FileSystemRepository $repository;

    private EmployeeRepository $employeeRepository;

    private User $user;

    public function __construct(EntityManager $entityManager, User $user)
    {
        $this->repository = $entityManager->getFileSystemRepository();
        $this->employeeRepository = $entityManager->getEmployeeRepository();
        $this->user = $user;
    }

    public function beforeRender(): void
    {
        $employee = $this->employeeRepository->find($this->user->id);
        assert($employee instanceof Employee);

        $usageSpace = $this->repository->getUsageByUser($employee);
        $freeSpace = $employee->diskSpace - $usageSpace;
        $percentage = ($usageSpace / $employee->diskSpace) * 100;


        $this->template->disk = [
            'percentage' => ((int)($percentage * ($p = 10 ** 2))) / $p
        ];

    }
}