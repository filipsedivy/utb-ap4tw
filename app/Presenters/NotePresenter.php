<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Note;
use App\Database\Entity;
use App\Database\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\UI\Multiplier;
use Nette\Http;

final class NotePresenter extends AuthPresenter
{
    private Note\View\ViewFactory $viewNoteFactory;

    private Note\Form\FormFactory $formNoteFactory;

    private Repository\NoteRepository $noteRepository;

    private ?Entity\Note $cursor = null;

    public function __construct(
        Note\View\ViewFactory $viewNoteFactory,
        Note\Form\FormFactory $formNoteFactory,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->viewNoteFactory = $viewNoteFactory;
        $this->formNoteFactory = $formNoteFactory;

        $repository = $entityManager->getRepository(Entity\Note::class);
        assert($repository instanceof Repository\NoteRepository);

        $this->noteRepository = $repository;
    }

    public function renderDefault(): void
    {
        $this->getPageInfo()->title = 'Poznámky';
        $this->template->notes = $this->entityManager->getNoteRepository()->getAccessibleNotes($this->authEmployee);
    }

    public function renderAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat poznámku';
        $this->getPageInfo()->backlink = $this->link('Note:');
    }

    public function actionEdit(int $id): void
    {
        $entity = $this->checkOneById(Entity\Note::class, $id);
        assert($entity instanceof Entity\Note);

        if ($entity->getCreator()->id !== $this->authEmployee->id) {
            $this->error('You dont have access to edit', Http\IResponse::S403_FORBIDDEN);
        }

        $this->cursor = $entity;
    }

    public function renderEdit(): void
    {
        $this->getPageInfo()->title = 'Upravit poznámku';
        $this->getPageInfo()->backlink = $this->link('Note:');
    }

    public function createComponentFormNote(): Note\Form\Form
    {
        $note = $this->cursor instanceof Entity\Note ? $this->cursor->id : null;
        $control = $this->formNoteFactory->create($note);
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
            $note = $this->checkOneById(Entity\Note::class, (int)$id);
            assert($note instanceof Entity\Note);

            $control = $this->viewNoteFactory->create($note);

            $control->onDelete[] = function () {
                $this->entityManager->flush();
                $this->flashMessage('Poznámka byla odstraněna', 'success');
                $this->redirect('this');
            };

            $control->onChangeVisibility[] = function (Entity\Note $note, bool $visibility) {
                $this->entityManager->flush();
                $message = $visibility ? 'veřejná' : 'skrytá';
                $this->flashMessage(sprintf('Poznámka byla nastavena jako %s.', $message), 'success');
                $this->redirect('this');
            };

            return $control;
        });
    }
}
