<?php declare(strict_types=1);

namespace App\Core\UI;

use Nette;
use ReflectionClass;

abstract class CoreControl extends Nette\Application\UI\Control
{
    /** @var bool */
    private $isLoaded = false;

    /** @var string|null */
    private $extendClassName;

    /**
     * @param array<mixed> $params
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

        $templateDirectory = dirname($reflectionClass->getFileName());
        $templateFile = $this->extendClassName . '.latte';
        $templatePath = $templateDirectory . '/' . $templateFile;

        $this->template->setFile($templatePath);
    }

    public function beforeRender(): void
    {
    }

    public function render(): void
    {
        $unique = Nette\Utils\Random::generate(10);

        $this->template->unique = $unique;
        $this->template->componentName = $this->extendClassName . '_' . $unique;
        $this->template->render();
    }

    private function getControlName(): string
    {
        return substr(strrchr(static::class, "\\"), 1);
    }
}