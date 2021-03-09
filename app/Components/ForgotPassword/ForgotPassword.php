<?php declare(strict_types=1);

namespace App\Components\ForgotPassword;

use App\Core\UI\CoreControl;
use Nette\Application\UI\Form;

final class ForgotPassword extends CoreControl
{
    public function createComponentForm(): Form
    {
        $form = new Form();

        $form->addText('username', 'Uživatelské jméno')
            ->setRequired('%label musí být zadáno');

        $form->addSubmit('process', 'Obnovit heslo');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form): void
    {

    }
}