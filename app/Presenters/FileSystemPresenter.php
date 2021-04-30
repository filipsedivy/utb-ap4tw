<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\FileSystem\FileSystem;
use App\Components\FileSystem\FileSystemFactory;

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
        return $this->fileSystemFactory->create();
    }
}