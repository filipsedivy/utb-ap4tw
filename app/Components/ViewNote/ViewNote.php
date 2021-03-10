<?php declare(strict_types=1);

namespace App\Components\ViewNote;

use App\Core\UI\CoreControl;
use App\Database\Entity\Note;
use App\Events\Note\DeleteNoteEvent;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onDelete()
 */
final class ViewNote extends CoreControl
{
    public array $onDelete = [];

    private Note $note;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(Note $note, EventDispatcherInterface $eventDispatcher)
    {
        $this->note = $note;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function beforeRender(): void
    {
        $this->template->note = $this->note;
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