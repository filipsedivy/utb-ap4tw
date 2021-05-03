<?php

declare(strict_types=1);

namespace App\Components\FormNote;

use App\Core\UI\CoreControl;
use App\Database\Entity\EntityManager;
use App\Database\Entity\Note;
use App\Events\Note\AddNoteEvent;
use App\Events\Note\UpdateNoteEvent;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;
use Nette\Utils\Arrays;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class FormNote extends CoreControl
{
    /** @var array<callable(): void> */
    public array $onCreate = [];

    /** @var array<callable(): void> */
    public array $onEdit = [];

    private EventDispatcherInterface $eventDispatcher;

    private ?Note $note;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManager $entityManager,
        ?int $id = null
    ) {
        $this->eventDispatcher = $eventDispatcher;

        if ($id) {
            $note = $entityManager->getNoteRepository()->find($id);

            if (!$note instanceof Note) {
                $this->error('Note not found');
            }

            $this->note = $note;
        } else {
            $this->note = null;
        }
    }

    public function beforeRender(): void
    {
        if ($this->note instanceof Note) {
            $this['form']->setDefaults([
                'note' => $this->note->note,
                'visibility' => $this->note->public
            ]);
        }
    }

    public function createComponentForm(): Form
    {
        $form = new Form();

        $form->addTextArea('note');

        $form->addCheckbox('visibility', 'Veřejná poznámka');

        if ($this->note instanceof Note) {
            $form->addSubmit('process', 'Upravit poznámku');
            $form->onSuccess[] = [$this, 'processUpdate'];
        } else {
            $form->addSubmit('process', 'Uložit poznámku');
            $form->onSuccess[] = [$this, 'processCreate'];
        }

        return $form;
    }

    public function processCreate(Form $form, FormData $values): void
    {
        $event = new AddNoteEvent($values->note, $values->visibility);
        $this->eventDispatcher->dispatch($event);

        Arrays::invoke($this->onCreate);
    }

    public function processUpdate(Form $form, FormData $values): void
    {
        if (!$this->note instanceof Note) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Note::class, []);
        }

        $event = new UpdateNoteEvent($this->note->getId(), $values->note, $values->visibility);
        $this->eventDispatcher->dispatch($event);

        Arrays::invoke($this->onEdit);
    }
}
