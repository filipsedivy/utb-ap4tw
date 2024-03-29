<?php

declare(strict_types=1);

namespace App\Core\UI;

use Nette;
use ReflectionClass;

abstract class CoreControl extends Nette\Application\UI\Control
{
    private bool $isLoaded = false;

    private ?string $extendClassName;

    /**
     * @param array<mixed> $params
     *
     * @throws Nette\Application\BadRequestException
     */
    public function loadState(array $params): void
    {
        if ($this->isLoaded === false) {
            $this->startup();
            $this->beforeRender();

            $this->isLoaded = true;
        }

        parent::loadState($params);
    }

    public function startup(): void
    {
        $reflectionClass = new ReflectionClass(static::class);

        $this->extendClassName = $this->getControlName();

        $reflectionFileName = $reflectionClass->getFileName();
        if ($reflectionFileName === false) {
            throw new \InvalidArgumentException();
        }

        $templateDirectory = dirname($reflectionFileName);
        $templateFile = $this->extendClassName . '.latte';
        $templatePath = $templateDirectory . '/' . $templateFile;

        if (!($this->template instanceof Nette\Application\UI\Template)) {
            return;
        }

        $this->template->setFile($templatePath);
    }

    public function beforeRender(): void
    {
    }

    public function render(): void
    {
        $unique = Nette\Utils\Random::generate(10);

        if (!($this->template instanceof Nette\Application\UI\Template)) {
            return;
        }

        $this->template->unique = $unique;
        $this->template->componentName = $this->extendClassName . '_' . $unique;
        $this->template->render();
    }

    private function getControlName(): string
    {
        $strClass = strrchr(static::class, "\\");
        if ($strClass === false) {
            throw new \InvalidArgumentException();
        }

        return substr($strClass, 1);
    }
}
