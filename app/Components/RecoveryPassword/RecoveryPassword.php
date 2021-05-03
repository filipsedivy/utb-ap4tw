<?php

declare(strict_types=1);

namespace App\Components\RecoveryPassword;

use App\Bootstrap;
use App\Database\Entity;
use App\Core\UI\CoreControl;
use App\Events\Mail\SendEmailTemplateEvent;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Utils\Arrays;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class RecoveryPassword extends CoreControl
{
    /** @var array<callable(): void> */
    public array $onSuccess = [];

    private Entity\RecoveryPassword $recoveryPassword;

    private Entity\EntityManager $entityManager;

    private Passwords $passwords;

    private User $user;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        Entity\RecoveryPassword $recoveryPassword,
        Entity\EntityManager $entityManager,
        User $user,
        Passwords $passwords,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->recoveryPassword = $recoveryPassword;
        $this->passwords = $passwords;
        $this->user = $user;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createComponentForm(): Form
    {
        $form = new Form();

        $form->addPassword('password', 'Nové heslo')
            ->setRequired('%label musí být nastaveno')
            ->addRule(Form::MIN_LENGTH, 'Délka hesla musí být alespoň %d znaků', 8);

        $form->addSubmit('submit', 'Uložit heslo');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form, FormData $values): void
    {
        $hash = $this->passwords->hash($values->password);
        $user = $this->entityManager->getEmployeeRepository()->find($this->recoveryPassword->user->id);
        if (!$user instanceof Entity\Employee) {
            $this->error('User not found');
        }
        $user->setPassword($hash);

        $this->entityManager->remove($this->recoveryPassword);
        $this->entityManager->flush();

        if ($this->user->isLoggedIn()) {
            $this->user->logout(true);
        }

        $message = new Message();
        $message->addTo($user->email);
        $message->setSubject('Heslo bylo obnovené');

        $event = new SendEmailTemplateEvent($message, Bootstrap::MAIL_DIR . '/templates/PasswordReset.latte');
        $this->eventDispatcher->dispatch($event);

        $this->user->login($this->recoveryPassword->user->username, $values->password);
        Arrays::invoke($this->onSuccess);
    }
}
