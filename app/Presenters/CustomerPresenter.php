<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\CustomerView\CustomerView;
use App\Components\CustomerView\CustomerViewFactory;

final class CustomerPresenter extends AuthPresenter
{
    private CustomerViewFactory $customerViewFactory;

    public function __construct(CustomerViewFactory $customerViewFactory)
    {
        parent::__construct();
        $this->customerViewFactory = $customerViewFactory;
    }

    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Zákazníci';
    }

    public function actionAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat zákazníka';
        $this->getPageInfo()->backlink = $this->link('Customer:');
    }

    public function createComponentCustomerView(): CustomerView
    {
        return $this->customerViewFactory->create();
    }
}