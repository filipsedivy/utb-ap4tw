<?php

declare(strict_types=1);

namespace App\Components\FileSystem;

interface FileSystemFactory
{
    public function create(): FileSystem;
}
