<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Customer;
use App\Database\Entity;

final class CustomerPresenter extends AuthPresenter
{
    /** @persistent */
    public ?int $id = null;

    private Customer\View\ViewFactory $customerViewFactory;

    private Customer\Form\FormFactory $formCustomerFactory;

    public function __construct(
        Customer\View\ViewFactory $customerViewFactory,
        Customer\Form\FormFactory $formCustomerFactory
    ) {
        parent::__construct();
        $this->customerViewFactory = $customerViewFactory;
        $this->formCustomerFactory = $formCustomerFactory;
    }

    public function renderDefault(): void
    {
        $this->pageInfo->title = 'Zákazníci';
    }

    public function renderAdd(): void
    {
        if ($this->id !== null) {
            $this->error();
        }

        $this->pageInfo->title = 'Přidat zákazníka';
        $this->pageInfo->backlink = $this->link('Customer:', ['id' => null]);
    }

    public function renderEdit(int $id): void
    {
        $entity = $this->checkOneById(Entity\Customer::class, $id);
        assert($entity instanceof Entity\Customer);

        $this->pageInfo->title = 'Upravit zákazníka';
        $this->pageInfo->subtitle = $entity->getName();
        $this->pageInfo->backlink = $this->link('Customer:', ['id' => null]);
    }

    public function createComponentCustomerView(): Customer\View\View
    {
        return $this->customerViewFactory->create();
    }

    public function createComponentFormCustomer(): Customer\Form\Form
    {
        $control = $this->formCustomerFactory->create($this->id);
        $control->onCreate[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Zákazník byl úspěšně vytvořen', 'success');
            $this->redirect('Customer:', ['id' => null]);
        };

        $control->onEdit[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Zákazník byl úspěšně upraven', 'success');
            $this->redirect('Customer:', ['id' => null]);
        };

        $control->onArchived[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Zákazník byl úspěšně archivován', 'success');
            $this->redirect('Customer:', ['id' => null]);
        };

        $control->onCancelArchived[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Zákazník byl odebrán z archivu', 'success');
            $this->redirect('this');
        };

        return $control;
    }
}
