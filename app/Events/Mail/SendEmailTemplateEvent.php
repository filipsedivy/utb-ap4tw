<?php

declare(strict_types=1);

namespace App\Events\Mail;

use Nette;

/**
 * @property-read string $templatePath
 * @property-read array $params
 * @property-read Nette\Mail\Message $message
 */
final class SendEmailTemplateEvent
{
    use Nette\SmartObject;

    private string $templatePath;

    /** @var array<mixed, mixed> */
    private array $params;

    private Nette\Mail\Message $message;

    /**
     * @param array<mixed, mixed> $params
     */
    public function __construct(Nette\Mail\Message $message, string $templatePath, array $params = [])
    {
        $this->templatePath = $templatePath;
        $this->params = $params;
        $this->message = $message;
    }

    /**
     * @param mixed $value
     */
    public function addParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getTemplatePath(): string
    {
        if (!file_exists($this->templatePath)) {
            throw new \LogicException('Template not found');
        }

        return $this->templatePath;
    }

    public function getMessage(): Nette\Mail\Message
    {
        return $this->message;
    }
}
