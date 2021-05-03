<?php

declare(strict_types = 1);

namespace App\Components\ForgotPassword;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Repository\EmployeeRepository;
use App\Events\RecoveryPassword\SendRecoveryLinkEvent;
use Nette\Application\UI\Form;
use Nette\Utils\Arrays;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ForgotPassword extends CoreControl
{

    /** @var array<callable(): void> */
    public array $onSuccess = [];

    private EventDispatcherInterface $eventDispatcher;

    private EmployeeRepository $repository;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManager $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->repository = $entityManager->getEmployeeRepository();
    }

    public function createComponentForm(): Form
    {
        $form = new Form();

        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('%label musí být zadáno');

        $form->addSubmit('process', 'Obnovit heslo');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form, FormData $values): void
    {
        $employee = $this->repository->findOneBy(['username' => $values->username]);

        if ($employee instanceof Employee) {
            $event = new SendRecoveryLinkEvent($employee);
            $this->eventDispatcher->dispatch($event);
        }

        Arrays::invoke($this->onSuccess);
    }

}
