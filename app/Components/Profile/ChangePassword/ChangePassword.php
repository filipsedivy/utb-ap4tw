<?php declare(strict_types=1);

namespace App\Components\Profile\ChangePassword;

use App\Core\UI\CoreControl;
use App\Events\Employee\ChangePasswordEvent;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onPasswordChanged()
 */
final class ChangePassword extends CoreControl
{
    /** @var callable[] */
    public array $onPasswordChanged = [];

    private User $user;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(User $user, EventDispatcherInterface $eventDispatcher)
    {
        $this->user = $user;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createComponentChangePassword(): Form
    {
        $form = new Form();

        $form->addPassword('password', 'Heslo')
            ->setRequired('%label je nutné zadat')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí být delší než %d znaků', 5);

        $form->addPassword('checkPassword', 'Opakujte heslo')
            ->setOmitted()
            ->setRequired('%label je nutné zopakovat heslo')
            ->addRule(Form::EQUAL, 'Vyplněné heslo se neshoduje', $form['password']);

        $form->addSubmit('process', 'Nastavit heslo');

        $form->onSuccess[] = [$this, 'onSuccess'];

        return $form;
    }

    public function onSuccess(Form $form): void
    {
        $data = $form->getValues(FormData::class);

        $event = new ChangePasswordEvent($this->user->id, $data->password);
        $this->eventDispatcher->dispatch($event);

        $this->onPasswordChanged();
    }
}