<?php declare(strict_types=1);

namespace App\Components\Profile\ChangePassword;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Repository\EmployeeRepository;
use App\Events\Employee\ChangePasswordEvent;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
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

    private EmployeeRepository $employeeRepository;

    private EventDispatcherInterface $eventDispatcher;

    private Passwords $passwords;

    public function __construct(User $user,
                                EventDispatcherInterface $eventDispatcher,
                                EntityManager $entityManager,
                                Passwords $passwords)
    {
        $this->user = $user;
        $this->eventDispatcher = $eventDispatcher;
        $this->employeeRepository = $entityManager->getEmployeeRepository();
        $this->passwords = $passwords;
    }

    public function createComponentChangePassword(): Form
    {
        $form = new Form();

        $form->addPassword('oldPassword', 'Původní heslo')
            ->setRequired('%label musí být vyplněné');

        $form->addPassword('newPassword', 'Heslo')
            ->setRequired('%label je nutné zadat')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí být delší než %d znaků', 5)
            ->addRule(Form::NOT_EQUAL, 'Nové heslo nesmí být stejné jako původní', $form['oldPassword']);

        $form->addPassword('checkPassword', 'Opakujte heslo')
            ->setOmitted()
            ->setRequired('%label je nutné zopakovat heslo')
            ->addRule(Form::EQUAL, 'Vyplněné heslo se neshoduje', $form['newPassword']);

        $form->addSubmit('process', 'Nastavit heslo');

        $form->onSuccess[] = [$this, 'onSuccess'];

        return $form;
    }

    public function onSuccess(Form $form): void
    {
        $data = $form->getValues(FormData::class);

        $employee = $this->employeeRepository->find($this->user->id);

        if (!$employee instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [$this->user->id]);
        }

        if (!$this->passwords->verify($data->oldPassword, $employee->getPassword())) {
            $form->addError('Staré heslo se neshoduje.');
            return;
        }

        $event = new ChangePasswordEvent($this->user->id, $data->newPassword);
        $this->eventDispatcher->dispatch($event);

        $this->onPasswordChanged();
    }
}