<?php declare(strict_types=1);

namespace App\Components\Profile\ChangePersonalData;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Events\Employee\ChangePersonalDataEvent;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onUpdate()
 */
final class ChangePersonalData extends CoreControl
{
    /** @var callable[] */
    public array $onUpdate = [];

    private Employee $employee;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManager $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                User $user)
    {
        $employeeRepository = $entityManager->getEmployeeRepository();
        $employee = $employeeRepository->find($user->id);

        if (!$employee instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [$user->id]);
        }

        $this->employee = $employee;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createComponentChangeForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Jméno')
            ->setDefaultValue($this->employee->getName())
            ->setRequired('%label musí být vyplněné');

        $form->addSubmit('process', 'Uložit údaje');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    public function processForm(Form $form): void
    {
        $data = $form->getValues(FormData::class);

        $event = new ChangePersonalDataEvent($this->employee->getId(), $data->name);
        $this->eventDispatcher->dispatch($event);

        $this->onUpdate();
    }
}