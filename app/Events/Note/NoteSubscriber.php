<?php

declare(strict_types=1);

namespace App\Events\Note;

use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use App\Database\Entity\Note;
use Doctrine\ORM\EntityNotFoundException;
use DateTime;
use Nette\Security\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NoteSubscriber implements EventSubscriberInterface
{
    private EntityManager $entityManager;

    private User $user;

    public function __construct(
        EntityManager $entityManager,
        User $user
    ) {
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AddNoteEvent::class => 'addNote',
            DeleteNoteEvent::class => 'deleteNote',
            UpdateNoteEvent::class => 'updateNote'
        ];
    }

    public function addNote(AddNoteEvent $event): void
    {
        $note = new Note();
        $note->setNote($event->getNote());

        $employee = $event->getEmployee() instanceof Employee ?
            $event->getEmployee() :
            $this->entityManager->getEmployeeRepository()->findOneBy(['id' => $this->user->getId()]);

        assert($employee instanceof Employee);

        $note->setCreator($employee);

        if ($event->getVisibility() !== null) {
            $note->setPrivate(!$event->getVisibility());
        }

        $this->entityManager->persist($note);
    }

    public function deleteNote(DeleteNoteEvent $event): void
    {
        $note = $this->entityManager->getNoteRepository()->find($event->getId());

        if (!$note instanceof Note) {
            $userId = (string)$event->getId();
            throw EntityNotFoundException::fromClassNameAndIdentifier(Note::class, [$userId]);
        }

        $this->entityManager->remove($note);
    }

    public function updateNote(UpdateNoteEvent $event): void
    {
        $note = $this->entityManager->getNoteRepository()->find($event->getId());

        if (!$note instanceof Note) {
            $userId = (string)$event->getId();
            throw EntityNotFoundException::fromClassNameAndIdentifier(Note::class, [$userId]);
        }

        if ($event->getNote() !== null) {
            $note->setNote($event->getNote());
        }

        $note->setEdited(new DateTime());

        if ($event->getVisibility() !== null) {
            $note->setPrivate(!$event->getVisibility());
        }
    }
}
