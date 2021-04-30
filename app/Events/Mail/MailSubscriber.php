<?php

declare(strict_types=1);

namespace App\Events\Mail;

use Latte\Engine;
use Nette\Mail\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MailSubscriber implements EventSubscriberInterface
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SendEmailTemplateEvent::class => 'sendEmailTemplate'
        ];
    }

    public function sendEmailTemplate(SendEmailTemplateEvent $event): void
    {
        $message = $event->message;
        $message->setFrom('crm@development.dev');

        $latte = new Engine();
        $message->setHtmlBody(
            $latte->renderToString($event->getTemplatePath(), $event->params)
        );

        $this->mailer->send($message);
    }
}
