<?php

declare(strict_types=1);

namespace App\Components\Note\Form;

use App\Database\Entity;
use App\Events\Note;
use App\Core;
use Doctrine\ORM;
use Nette\Application\UI;
use Nette\Utils;
use Symfony\Component\EventDispatcher;

final class Form extends Core\UI\CoreControl
{
    /** @var array<callable(): void> */
    public array $onCreate = [];

    /** @var array<callable(): void> */
    public array $onEdit = [];

    private EventDispatcher\EventDispatcherInterface $eventDispatcher;

    private ?Entity\Note $note;

    public function __construct(
        EventDispatcher\EventDispatcherInterface $eventDispatcher,
        Entity\EntityManager $entityManager,
        ?int $id = null
    ) {
        $this->eventDispatcher = $eventDispatcher;

        if ($id) {
            $note = $entityManager->getNoteRepository()->find($id);

            if (!$note instanceof Entity\Note) {
                $this->error('Note not found');
            }

            $this->note = $note;
        } else {
            $this->note = null;
        }
    }

    public function beforeRender(): void
    {
        if (!($this->note instanceof Entity\Note)) {
            return;
        }

        $this['form']->setDefaults([
            'note' => $this->note->note,
            'visibility' => $this->note->public
        ]);
    }

    public function createComponentForm(): UI\Form
    {
        $form = new UI\Form();

        $form->addTextArea('note');

        $form->addCheckbox('visibility', 'Veřejná poznámka');

        if ($this->note instanceof Entity\Note) {
            $form->addSubmit('process', 'Upravit poznámku');
            $form->onSuccess[] = [$this, 'processUpdate'];
        } else {
            $form->addSubmit('process', 'Uložit poznámku');
            $form->onSuccess[] = [$this, 'processCreate'];
        }

        return $form;
    }

    public function processCreate(UI\Form $form, Data $values): void
    {
        $event = new Note\AddNoteEvent($values->note, $values->visibility);
        $this->eventDispatcher->dispatch($event);

        Utils\Arrays::invoke($this->onCreate);
    }

    public function processUpdate(UI\Form $form, Data $values): void
    {
        if (!$this->note instanceof Entity\Note) {
            throw ORM\EntityNotFoundException::fromClassNameAndIdentifier(Entity\Note::class, []);
        }

        $event = new Note\UpdateNoteEvent($this->note->getId(), $values->note, $values->visibility);
        $this->eventDispatcher->dispatch($event);

        Utils\Arrays::invoke($this->onEdit);
    }
}
