<?php declare(strict_types=1);

namespace App\Components\SignIn;

use App\Core\UI\CoreControl;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

/**
 * @method void onSuccess()
 */
final class SignIn extends CoreControl
{
    public array $onSuccess = [];

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createComponentForm(): Form
    {
        $form = new Form();

        $username = $form->addText('username', 'Uživatelské jméno')
            ->setRequired('%label je nutné vyplnit');
        $username->getControlPrototype()
            ->setAttribute('placeholder', $username->getLabel()->getText());

        $password = $form->addPassword('password', 'Heslo')
            ->setRequired('%label je nutné vyplnit');
        $password->getControlPrototype()
            ->setAttribute('placeholder', $password->getLabel()->getText());

        $form->addSubmit('process', 'Přihlásit se');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form): void
    {
        $values = $form->getValues(new FormData);
        try {
            $this->user->login($values->username, $values->password);
            $this->onSuccess();
        } catch (AuthenticationException $exception) {
            $form->addError('Jméno nebo heslo není správně zadané', false);
            return;
        }
    }
}