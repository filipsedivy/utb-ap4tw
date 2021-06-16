<?php

declare(strict_types=1);

namespace App\Components\Employee\Form;

use App\Core;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use Nette\Application\UI;
use Nette\Utils;

final class Form extends Core\UI\CoreControl
{
    /** @var array<callable(): void> */
    public array $onCreate = [];

    /** @var array<callable(): void> */
    public array $onUpdate = [];

    /** @var array<callable(): void> */
    public array $onDelete = [];

    private ?Employee $employee;

    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager, ?Employee $employee = null)
    {
        $this->employee = $employee;
        $this->entityManager = $entityManager;
    }

    public function beforeRender(): void
    {
        $this->template->hasEmployeeExists = $this->employee instanceof Employee;
        if (!($this->employee instanceof Employee)) {
            return;
        }

        $this['userForm']->setDefaults([
            'name' => $this->employee->name,
            'username' => $this->employee->username,
            'email' => $this->employee->email
        ]);
    }

    public function createComponentUserForm(): UI\Form
    {
        $form = new UI\Form();

        $form->addText('name', 'Jméno')
            ->setRequired('%label musí být vyplněno');

        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('%label musí být vyplněno');

        $form->addEmail('email', 'E-mailová adresa')
            ->setRequired('%label musí být vyplněná');

        if ($this->employee === null) {
            $form->addPassword('password', 'Heslo')
                ->setRequired('%label musí být vyplněno')
                ->addRule(UI\Form::MIN_LENGTH, 'Heslo musí být delší než %d znaků', 8);
        }

        $form->addSubmit('save', 'Uložit');

        $form->onSuccess[] = [$this, 'createUserForm'];

        return $form;
    }

    public function createUserForm(UI\Form $form, Data $data): void
    {
        if ($this->employee instanceof Employee) {
            $this->employee->setName($data->name);
            $this->employee->setUsername($data->username);
            $this->employee->setEmail($data->email);
            Utils\Arrays::invoke($this->onUpdate);
        } else {
            $employee = new Employee(
                $data->username,
                $data->password,
                $data->email,
                $data->name
            );
            $this->entityManager->persist($employee);
            Utils\Arrays::invoke($this->onCreate);
        }
    }

    public function handleDelete(): void
    {
        if ($this->employee === null) {
            throw new \InvalidArgumentException();
        }

        $this->entityManager->remove($this->employee);
        Utils\Arrays::invoke($this->onDelete);
    }
}
