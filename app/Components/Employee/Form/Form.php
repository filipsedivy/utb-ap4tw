<?php

declare(strict_types=1);

namespace App\Components\Employee\Form;

use App\Core;
use Nette\Application\UI;
use Nette\Utils;

final class Form extends Core\UI\CoreControl
{
    /** @var array<callable(): void> */
    public array $onSuccess = [];

    public function createComponentUserForm(): UI\Form
    {
        $form = new UI\Form();

        $form->addText('name', 'Jméno')
            ->setRequired('%label musí být vyplněno');

        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('%label musí být vyplněno');

        $form->addEmail('email', 'E-mailová adresa')
            ->setRequired('%label musí být vyplněná');

        $form->addPassword('password', 'Heslo')
            ->setRequired('%label musí být vyplněno')
            ->addRule(UI\Form::MIN_LENGTH, 'Heslo musí být delší než %d znaků', 8);

        $form->addSubmit('save', 'Uložit');

        $form->onSuccess[] = [$this, 'createUserForm'];

        return $form;
    }

    public function createUserForm(UI\Form $form, Data $data): void
    {
        Utils\Arrays::invoke($this->onSuccess);
    }
}
