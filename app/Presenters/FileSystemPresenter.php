<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\FileSystem\FileSystem;
use App\Components\FileSystem\FileSystemFactory;
use Nette\Application\Responses\FileResponse;

final class FileSystemPresenter extends AuthPresenter
{
    private FileSystemFactory $fileSystemFactory;

    public function __construct(FileSystemFactory $fileSystemFactory)
    {
        parent::__construct();
        $this->fileSystemFactory = $fileSystemFactory;
    }

    public function actionDefault(): void
    {
        $this->pageInfo->title = 'Soubory';
    }

    public function createComponentFileSystem(): FileSystem
    {
        $control = $this->fileSystemFactory->create();
        $control->onUpload[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Soubor byl úspěšně nahrán', 'success');
            $this->redirect('this');
        };

        $control->onDelete[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Soubor byl úspěšně odstraněn', 'success');
            $this->redirect('this');
        };

        $control->onDownload[] = function (\App\Database\Entity\FileSystem $fileSystem) {
            $response = new FileResponse(
                $fileSystem->path,
                $fileSystem->getName(),
                $fileSystem->contentType
            );

            $this->sendResponse($response);
        };

        return $control;
    }
}
