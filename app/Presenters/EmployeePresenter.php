<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Employee;
use App\Database;

final class EmployeePresenter extends AuthPresenter
{
    private Employee\Grid\GridFactory $gridFactory;

    private Employee\Form\FormFactory $formFactory;

    private ?Database\Entity\Employee $cursor = null;

    public function __construct(
        Employee\Grid\GridFactory $gridFactory,
        Employee\Form\FormFactory $formFactory
    ) {
        parent::__construct();
        $this->gridFactory = $gridFactory;
        $this->formFactory = $formFactory;
    }

    public function renderDefault(): void
    {
        $this->pageInfo->title = 'Zaměstnanci';
    }

    public function renderAdd(): void
    {
        $this->pageInfo->title = 'Přidat zaměstnance';
        $this->pageInfo->backlink = $this->link('default');
    }

    public function actionEdit(int $id): void
    {
        $entity = $this->checkOneById(Database\Entity\Employee::class, $id);
        assert($entity instanceof Database\Entity\Employee);

        if ($entity->id === $this->user->id) {
            $this->redirect('Profile:');
        }

        $this->cursor = $entity;
    }

    public function renderEdit(): void
    {
        assert($this->cursor instanceof Database\Entity\Employee);

        $this->pageInfo->title = 'Upravit zaměstnance';
        $this->pageInfo->subtitle = $this->cursor->name;
        $this->pageInfo->backlink = $this->link('default');
    }

    public function createComponentGrid(): Employee\Grid\Grid
    {
        return $this->gridFactory->create();
    }

    public function createComponentForm(): Employee\Form\Form
    {
        $control = $this->formFactory->create($this->cursor);
        $control->onCreate[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Uživatel byl úspěšně vytvořen', 'success');
            $this->redirect('Employee:');
        };

        $control->onUpdate[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Uživatel byl úspěšně aktualizován', 'success');
            $this->redirect('Employee:');
        };

        $control->onDelete[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Uživatel byl úspěšně odstraněn', 'success');
            $this->redirect('Employee:');
        };

        return $control;
    }
}
