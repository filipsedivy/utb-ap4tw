<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\Profile\ChangePassword\ {
    ChangePassword,
    ChangePasswordFactory
};

final class ProfilePresenter extends AuthPresenter
{
    private ChangePasswordFactory $changePasswordFactory;

    public function __construct(ChangePasswordFactory $changePasswordFactory)
    {
        parent::__construct();
        $this->changePasswordFactory = $changePasswordFactory;
    }

    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Úprava profilu';
    }

    public function actionChangePassword(): void
    {
        $this->getPageInfo()->title = 'Změna hesla';
    }

    public function createComponentChangePassword(): ChangePassword
    {
        $control = $this->changePasswordFactory->create();
        $control->onPasswordChanged[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Heslo bylo úspěšně změněno', 'success');
            $this->redirect('this');
        };

        return $control;
    }
}