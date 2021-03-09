<?php declare(strict_types=1);

namespace App\Presenters;

final class TaskPresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Úkoly';
    }

    public function actionAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat úkol';
        $this->getPageInfo()->backlink = $this->link('Task:');
    }
}