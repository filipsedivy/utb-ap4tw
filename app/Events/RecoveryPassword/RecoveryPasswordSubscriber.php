<?php

declare(strict_types=1);

namespace App\Events\RecoveryPassword;

use App\Bootstrap;
use App\Database\Entity\EntityManager;
use App\Database\Entity\RecoveryPassword;
use App\Events\Mail\SendEmailTemplateEvent;
use Nette\Application\LinkGenerator;
use Nette\Mail\Message;
use Nette\Utils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class RecoveryPasswordSubscriber implements EventSubscriberInterface
{
    private EventDispatcherInterface $eventDispatcher;

    private LinkGenerator $linkGenerator;

    private EntityManager $entityManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, LinkGenerator $linkGenerator, EntityManager $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->linkGenerator = $linkGenerator;
        $this->entityManager = $entityManager;
    }

    /** @return array<string, string> */
    public static function getSubscribedEvents(): array
    {
        return [
            SendRecoveryLinkEvent::class => 'sendRecoveryLink'
        ];
    }

    public function sendRecoveryLink(SendRecoveryLinkEvent $event): void
    {
        $token = Utils\Random::generate(60, '0-9a-zA-Z');
        $link = $this->linkGenerator->link('Sign:forgotPassword', ['id' => $token]);
        $expire = Utils\DateTime::from('now')->modify('+5 hours');

        $recoveryPassword = new RecoveryPassword();
        $recoveryPassword->setUser($event->employee);
        $recoveryPassword->setExpiredAt($expire);
        $recoveryPassword->setToken($token);
        $this->entityManager->persist($recoveryPassword);

        $message = new Message();
        $message->setSubject('Obnovení hesla do aplikace CRM');
        $message->addTo($event->employee->email);

        $sendEmail = new SendEmailTemplateEvent(
            $message,
            Bootstrap::MAIL_DIR . '/templates/PasswordRecovery.latte',
            [
                'expire' => $expire,
                'link' => $link
            ]
        );
        $this->eventDispatcher->dispatch($sendEmail);
    }
}
