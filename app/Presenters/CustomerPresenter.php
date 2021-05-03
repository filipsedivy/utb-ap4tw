<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\CustomerView\CustomerView;
use App\Components\CustomerView\CustomerViewFactory;
use App\Components\FormCustomer\FormCustomer;
use App\Components\FormCustomer\FormCustomerFactory;
use App\Database\Entity\Customer;

final class CustomerPresenter extends AuthPresenter
{
    /** @persistent */
    public ?int $id = null;

    private CustomerViewFactory $customerViewFactory;

    private FormCustomerFactory $formCustomerFactory;

    public function __construct(
        CustomerViewFactory $customerViewFactory,
        FormCustomerFactory $formCustomerFactory
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
        $entity = $this->checkOneById(Customer::class, $id);
        assert($entity instanceof Customer);

        $this->pageInfo->title = 'Upravit zákazníka';
        $this->pageInfo->subtitle = $entity->getName();
        $this->pageInfo->backlink = $this->link('Customer:', ['id' => null]);
    }

    public function createComponentCustomerView(): CustomerView
    {
        return $this->customerViewFactory->create();
    }

    public function createComponentFormCustomer(): FormCustomer
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
