<?php declare(strict_types=1);

namespace App\Presenters;

final class CustomerPresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Zákazníci';
    }

    public function actionAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat zákazníka';
        $this->getPageInfo()->backlink = $this->link('Customer:');
    }
}