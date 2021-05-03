<?php

declare(strict_types = 1);

namespace App\Components\Profile\ChangePersonalData;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Events\Employee\ChangePersonalDataEvent;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\Arrays;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function assert;
use App\Components\Profile\ChangePersonalData\EntityNotFoundException;

final class ChangePersonalData extends CoreControl
{

	/** @var array<callable(): void> */
	public array $onUpdate = [];

	private Employee $employee;

	private EventDispatcherInterface $eventDispatcher;

	public function __construct(EntityManager $entityManager, EventDispatcherInterface $eventDispatcher, User $user) {
		$employeeRepository = $entityManager->getEmployeeRepository();
		$employee = $employeeRepository->find($user->id);

		if (!$employee instanceof Employee) {
			throw EntityNotFoundException::fromClassNameAndIdentifier(
				Employee::class,
				[$user->id]
			);
		}

		$this->employee = $employee;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function createComponentChangeForm(): Form
	{
		$form = new Form;

		$form->addText('name', 'Jméno')
			->setDefaultValue($this->employee->getName())
			->setRequired('%label musí být vyplněné');

		$form->addSubmit('process', 'Uložit údaje');

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}

	public function processForm(Form $form): void
	{
		$data = $form->getValues(new FormData());
		assert($data instanceof FormData);

		$event = new ChangePersonalDataEvent($this->employee->getId(), $data->name);
		$this->eventDispatcher->dispatch($event);

		Arrays::invoke($this->onUpdate);
	}

}
