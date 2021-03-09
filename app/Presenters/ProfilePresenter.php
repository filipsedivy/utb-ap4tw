<?php declare(strict_types=1);

namespace App\Presenters;

final class ProfilePresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Úprava profilu';
    }

    public function actionChangePassword(): void
    {
        $this->getPageInfo()->title = 'Změna hesla';
    }
}