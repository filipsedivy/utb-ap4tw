<?php

declare(strict_types=1);

namespace App\Components\ViewNote;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Entity\Note;
use App\Events\Note\DeleteNoteEvent;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Security\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onDelete()
 */
final class ViewNote extends CoreControl
{
    /** @var callable[] */
    public array $onDelete = [];

    private Note $note;

    private EventDispatcherInterface $eventDispatcher;

    private Employee $employee;

    public function __construct(
        Note $note,
        EventDispatcherInterface $eventDispatcher,
        User $user,
        EntityManager $entityManager
    ) {
        $this->note = $note;
        $this->eventDispatcher = $eventDispatcher;

        $employee = $entityManager->getEmployeeRepository()->find($user->id);
        assert($employee instanceof Employee);
        $this->employee = $employee;
    }

    public function beforeRender(): void
    {
        $this->template->note = $this->note;
        $this->template->showFooter = $this->employee->id === $this->note->getCreator()->id;
    }

    public function handleDelete(int $id): void
    {
        $event = new DeleteNoteEvent($id);

        try {
            $this->eventDispatcher->dispatch($event);
            $this->onDelete();
        } catch (EntityNotFoundException $exception) {
            $this->error('Entity not found');
        }
    }
}
