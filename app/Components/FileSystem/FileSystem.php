<?php

declare(strict_types=1);

namespace App\Components\FileSystem;

use App\Bootstrap;
use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Repository\EmployeeRepository;
use App\Database\Repository\FileSystemRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Security\User;
use Nette\Utils\ArrayHash;
use Nette\Utils;
use App\Database\Entity;

final class FileSystem extends CoreControl
{
    /** @var array<callable(): void> */
    public array $onUpload = [];

    /** @var array<callable(): void> */
    public array $onDelete = [];

    /** @var array<callable(): void> */
    public array $onDownload = [];

    private FileSystemRepository $repository;

    private EmployeeRepository $employeeRepository;

    private EntityManager $entityManager;

    private User $user;

    public function __construct(EntityManager $entityManager, User $user)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getFileSystemRepository();
        $this->employeeRepository = $entityManager->getEmployeeRepository();
        $this->user = $user;
    }

    public function beforeRender(): void
    {
        $employee = $this->employeeRepository->find($this->user->id);
        assert($employee instanceof Employee);

        $usageSpace = $this->repository->getUsageByUser($employee);
        $percentage = $usageSpace / $employee->diskSpace * 100;

        $files = $this->repository->findBy([
            'user' => $employee
        ]);


        $this->template->files = $files;
        $this->template->disk = [
            'percentage' => ((int)($percentage * ($p = 10 ** 2))) / $p
        ];
    }

    public function createComponentUpload(): Form
    {
        $form = new Form;

        $form->addUpload('file', 'Soubor')
            ->addRule(Form::REQUIRED, 'Soubor musí být zvolený')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru 500 kB', 500 * 1024);

        $form->addSubmit('upload', 'Nahrát soubor');

        $form->onSuccess[] = [$this, 'onUploaded'];

        return $form;
    }

    public function onUploaded(Form $form, ArrayHash $values): void
    {
        $employee = $this->employeeRepository->find($this->user->id);
        if (!$employee instanceof Employee) {
            throw new \LogicException();
        }

        $file = $values->file;
        assert($file instanceof FileUpload);

        $path = Bootstrap::UPLOAD_DIR . '/employee/' . $employee->id . '/' . $file->name;

        Utils\FileSystem::createDir(Bootstrap::UPLOAD_DIR . '/employee/' . $employee->id);
        $file->move($path);

        $fileSystem = new Entity\FileSystem();
        $fileSystem->setName($file->name);
        $fileSystem->setSize($file->size);
        $fileSystem->setUser($employee);
        $fileSystem->setPath($path);
        $fileSystem->setContentType($file->contentType);

        $this->entityManager->persist($fileSystem);

        Utils\Arrays::invoke($this->onUpload);
    }

    public function handleDelete(int $id): void
    {
        $fileSystem = $this->repository->find($id);
        assert($fileSystem instanceof Entity\FileSystem);

        $employee = $this->employeeRepository->find($this->user->id);
        assert($employee instanceof Employee);

        if ($employee->id !== $fileSystem->user->id) {
            throw new \LogicException();
        }

        Utils\FileSystem::delete($fileSystem->getPath());
        $this->entityManager->remove($fileSystem);

        Utils\Arrays::invoke($this->onDelete);
    }

    public function handleDownload(int $id): void
    {
        $fileSystem = $this->repository->find($id);
        assert($fileSystem instanceof Entity\FileSystem);

        Utils\Arrays::invoke($this->onDownload, $fileSystem);
    }
}
