<?php

declare(strict_types=1);

namespace App\Components\ViewNote;

use App\Core\UI\CoreControl;
use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Entity\Note;
use App\Events\Note\DeleteNoteEvent;
use App\Events\Note\UpdateNoteEvent;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Security\User;
use Nette\Utils\Arrays;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ViewNote extends CoreControl
{
    /** @var array<callable(): void> */
    public array $onDelete = [];

    /** @var array<callable(Note, bool): void> */
    public array $onChangeVisibility = [];

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

    public function handleDelete(): void
    {
        $event = new DeleteNoteEvent($this->note->id);
        $this->eventDispatcher->dispatch($event);
        Arrays::invoke($this->onDelete);
    }

    public function handlePrivate(): void
    {
        $event = new UpdateNoteEvent($this->note->id, null, false);
        $this->eventDispatcher->dispatch($event);
        Arrays::invoke($this->onChangeVisibility, $this->note, false);
    }

    public function handlePublic(): void
    {
        $event = new UpdateNoteEvent($this->note->id, null, true);
        $this->eventDispatcher->dispatch($event);
        Arrays::invoke($this->onChangeVisibility, $this->note, true);
    }
}
