<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\FormNote\FormNote;
use App\Components\FormNote\FormNoteFactory;
use App\Components\ViewNote\ViewNoteFactory;
use App\Database\Entity\Note;
use App\Database\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\UI\Multiplier;
use Nette\Http;

final class NotePresenter extends AuthPresenter
{
    private ViewNoteFactory $viewNoteFactory;

    private FormNoteFactory $formNoteFactory;

    private NoteRepository $noteRepository;

    private ?int $note = null;

    public function __construct(
        ViewNoteFactory $viewNoteFactory,
        FormNoteFactory $formNoteFactory,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->viewNoteFactory = $viewNoteFactory;
        $this->formNoteFactory = $formNoteFactory;

        $noteRepository = $entityManager->getRepository(Note::class);
        assert($noteRepository instanceof NoteRepository);
        $this->noteRepository = $noteRepository;
    }

    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Poznámky';
        $this->template->notes = $this->entityManager->getNoteRepository()->getAccessibleNotes();
    }

    public function actionAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat poznámku';
        $this->getPageInfo()->backlink = $this->link('Note:');
    }

    public function actionEdit(int $id): void
    {
        $entity = $this->noteRepository->find($id);
        if (!$entity instanceof Note) {
            $this->error('Note not found', Http\IResponse::S403_FORBIDDEN);
        }

        $this->getPageInfo()->title = 'Upravit poznámku';
        $this->getPageInfo()->backlink = $this->link('Note:');
        $this->note = $id;
    }

    public function createComponentFormNote(): FormNote
    {
        $control = $this->formNoteFactory->create($this->note);
        $control->onCreate[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Poznámka byla úspěšně uložena', 'success');
            $this->redirect('Note:');
        };

        $control->onEdit[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Poznámka byla úspěšně upravena', 'success');
            $this->redirect('Note:');
        };

        return $control;
    }

    public function createComponentNote(): Multiplier
    {
        return new Multiplier(function (string $id) {
            $note = $this->entityManager->getNoteRepository()->findOneBy(['id' => $id]);
            if (!$note instanceof Note) {
                $this->error('Note not found', Http\IResponse::S403_FORBIDDEN);
            }

            $control = $this->viewNoteFactory->create($note);

            $control->onDelete[] = function () {
                $this->entityManager->flush();
                $this->flashMessage('Poznámka byla odstraněna', 'success');
                $this->redirect('this');
            };

            return $control;
        });
    }
}
