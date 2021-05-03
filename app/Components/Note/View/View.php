<?php

declare(strict_types=1);

namespace App\Components\Note\View;

use App\Core;
use App\Database\Entity;
use App\Events\Note;
use Nette\Security;
use Nette\Utils;
use Symfony\Component\EventDispatcher;

final class View extends Core\UI\CoreControl
{
    /** @var array<callable(): void> */
    public array $onDelete = [];

    /** @var array<callable(Entity\Note, bool): void> */
    public array $onChangeVisibility = [];

    private Entity\Note $note;

    private EventDispatcher\EventDispatcherInterface $eventDispatcher;

    private Entity\Employee $employee;

    public function __construct(
        Entity\Note $note,
        EventDispatcher\EventDispatcherInterface $eventDispatcher,
        Security\User $user,
        Entity\EntityManager $entityManager
    ) {
        $this->note = $note;
        $this->eventDispatcher = $eventDispatcher;

        $employee = $entityManager->getEmployeeRepository()->find($user->id);
        assert($employee instanceof Entity\Employee);
        $this->employee = $employee;
    }

    public function beforeRender(): void
    {
        $this->template->note = $this->note;
        $this->template->showFooter = $this->employee->id === $this->note->getCreator()->id;
    }

    public function handleDelete(): void
    {
        $event = new Note\DeleteNoteEvent($this->note->id);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onDelete);
    }

    public function handlePrivate(): void
    {
        $event = new Note\UpdateNoteEvent($this->note->id, null, false);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onChangeVisibility, $this->note, false);
    }

    public function handlePublic(): void
    {
        $event = new Note\UpdateNoteEvent($this->note->id, null, true);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onChangeVisibility, $this->note, true);
    }
}
