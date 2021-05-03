<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components\Profile\ChangePersonalData\ChangePersonalData;
use App\Components\Profile\ChangePersonalData\ChangePersonalDataFactory;
use App\Components\Profile\ChangePassword\ {
    \ChangePassword,
    \ChangePasswordFactory
};
use const ChangePassword;
use const ChangePasswordFactory;

final class ProfilePresenter extends AuthPresenter
{

    private ChangePasswordFactory $changePasswordFactory;

    private ChangePersonalDataFactory $changePersonalDataFactory;

    public function __construct(
        ChangePasswordFactory $changePasswordFactory,
        ChangePersonalDataFactory $changePersonalDataFactory
    ) {
        parent::__construct();

        $this->changePasswordFactory = $changePasswordFactory;
        $this->changePersonalDataFactory = $changePersonalDataFactory;
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
        $control->onPasswordChanged[] = function (): void {
            $this->entityManager->flush();
            $this->flashMessage('Heslo bylo úspěšně změněno', 'success');
            $this->redirect('this');
        };

        return $control;
    }

    public function createComponentChangePersonalData(): ChangePersonalData
    {
        $control = $this->changePersonalDataFactory->create();
        $control->onUpdate[] = function (): void {
            $this->entityManager->flush();
            $this->flashMessage('Uživatelský profil byl úspěšně uložen', 'success');
            $this->redirect('this');
        };

        return $control;
    }

}
