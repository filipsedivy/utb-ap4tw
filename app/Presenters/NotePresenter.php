<?php declare(strict_types=1);

namespace App\Presenters;

final class NotePresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Poznámky';
    }

    public function actionAdd(): void
    {
        $this->getPageInfo()->title = 'Přidat poznámku';
        $this->getPageInfo()->backlink = $this->link('Note:');
    }
}