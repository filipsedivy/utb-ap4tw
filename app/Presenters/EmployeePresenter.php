<?php

declare(strict_types=1);

namespace App\Presenters;

final class EmployeePresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->pageInfo->title = 'Zaměstnanci';
    }

    public function actionAdd(): void
    {
        $this->pageInfo->title = 'Přidat zaměstnance';
        $this->pageInfo->backlink = $this->link('default');
    }

    public function actionEdit(): void
    {
        $this->pageInfo->title = 'Upravit zaměstnance';
        $this->pageInfo->backlink = $this->link('default');
    }
}
