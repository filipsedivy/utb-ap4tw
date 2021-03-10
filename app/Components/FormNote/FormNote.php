<?php declare(strict_types=1);

namespace App\Components\FormNote;

use App\Core\UI\CoreControl;
use App\Database\Entity\EntityManager;
use App\Database\Entity\Note;
use App\Events\Note\AddNoteEvent;
use App\Events\Note\UpdateNoteEvent;
use Nette\Application\UI\Form;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onCreate()
 * @method void onEdit()
 */
final class FormNote extends CoreControl
{
    public array $onCreate = [];

    public array $onEdit = [];

    private EventDispatcherInterface $eventDispatcher;

    private ?Note $note;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManager $entityManager, ?int $id = null)
    {
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

    public function createComponentForm(): Form
    {
        $form = new Form();

        $form->addTextArea('note');

        if ($this->note instanceof Note) {
            $form->addSubmit('process', 'Upravit poznámku');

            $form->setDefaults([
                'note' => $this->note->getNote()
            ]);

            $form->onSuccess[] = [$this, 'processUpdate'];
        } else {
            $form->addSubmit('process', 'Uložit poznámku');

            $form->onSuccess[] = [$this, 'processCreate'];
        }

        return $form;
    }

    public function processCreate(Form $form): void
    {
        $values = $form->getValues(new FormData);

        $event = new AddNoteEvent($values->note);
        $this->eventDispatcher->dispatch($event);

        $this->onCreate();
    }

    public function processUpdate(Form $form): void
    {
        $values = $form->getValues(new FormData);

        $event = new UpdateNoteEvent($this->note->getId(), $values->note);
        $this->eventDispatcher->dispatch($event);

        $this->onEdit();
    }
}