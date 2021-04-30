<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Dashboard\UI;
use App\Database\Entity;

final class HomepagePresenter extends AuthPresenter
{
    /** @inject */
    public UI\Total\TotalFactory $totalFactory;

    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Nástěnka';
    }

    public function createComponentTotalCustomers(): UI\Total\Total
    {
        $control = $this->totalFactory->create(Entity\Customer::class);
        $control->setTitle('Celkový počet zákazníků');
        $control->setIcon('user');
        $control->setColor();
        return $control;
    }
}
