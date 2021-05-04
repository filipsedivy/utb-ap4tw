<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components;
use App\Database\Entity;

final class SignPresenter extends BasePresenter
{
    private Components\SignIn\SignInFactory $signInFactory;

    private Components\ForgotPassword\ForgotPasswordFactory $forgotPasswordFactory;

    private Components\RecoveryPassword\RecoveryPasswordFactory $recoveryPasswordFactory;

    private ?Entity\RecoveryPassword $cursor = null;

    public function __construct(
        Components\SignIn\SignInFactory $signInFactory,
        Components\ForgotPassword\ForgotPasswordFactory $forgotPasswordFactory,
        Components\RecoveryPassword\RecoveryPasswordFactory $recoveryPasswordFactory
    ) {
        parent::__construct();

        $this->signInFactory = $signInFactory;
        $this->forgotPasswordFactory = $forgotPasswordFactory;
        $this->recoveryPasswordFactory = $recoveryPasswordFactory;
    }

    public function actionDefault(): void
    {
        $this->redirect('Sign:in');
    }

    public function actionIn(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->flashMessage('Uživatel již je přihlášen', 'success');
            $this->redirect('Homepage:');
        }

        $this->getPageInfo()->title = 'Přihlášení';
    }

    public function actionOut(): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $user = $this->entityManager->getEmployeeRepository()->find($this->user->id);
            if ($user instanceof Entity\Employee) {
                $user->setAuthToken(null);
                $this->entityManager->flush($user);
            }

            $this->getUser()->logout(true);
        }

        $this->redirect("Sign:in");
    }

    public function actionForgotPassword(?string $id = null): void
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->flashMessage('Uživatel již je přihlášen', 'success');
            $this->redirect('Homepage:');
        }

        if ($id === null) {
            $this->pageInfo->title = 'Zapomenuté heslo';
            $this->setView('forgotPassword');
        } else {
            $this->pageInfo->title = 'Obnovení hesla';
            $this->setView('setPassword');

            $cursor = $this->entityManager->getRecoveryPasswordRepository()->findByToken($id);
            if (!$cursor instanceof Entity\RecoveryPassword) {
                $this->flashMessage('Adresa pro obnovu hesla již vypršela nebo je neplatná', 'warning');
                $this->redirect('in');
            }

            $this->cursor = $cursor;
        }
    }

    public function createComponentSignIn(): Components\SignIn\SignIn
    {
        $control = $this->signInFactory->create();
        $control->onSuccess[] = function () {
            $this->redirect('Homepage:');
        };

        return $control;
    }

    public function createComponentForgotPassword(): Components\ForgotPassword\ForgotPassword
    {
        $control = $this->forgotPasswordFactory->create();
        $control->onSuccess[] = function () {
            $this->entityManager->flush();
            $this->flashMessage('Pokud uživatelský účet existuje, byl zaslán odkaz pro obnovu hesla', 'success');
            $this->redirect('in');
        };

        return $control;
    }

    public function createComponentRecoveryPassword(): \App\Components\RecoveryPassword\RecoveryPassword
    {
        if (!$this->cursor instanceof Entity\RecoveryPassword) {
            $this->error('Cursor not exists');
        }

        $control = $this->recoveryPasswordFactory->create($this->cursor);
        $control->onSuccess[] = function () {
            $this->flashMessage('Uživatelské heslo bylo úspěšně změněno', 'success');
            $this->redirect('Homepage:');
        };

        return $control;
    }
}
