<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\ForgotPassword\ForgotPassword;
use App\Components\ForgotPassword\ForgotPasswordFactory;
use App\Components\SignIn\SignIn;
use App\Components\SignIn\SignInFactory;
use App\Database\Entity\Employee;

final class SignPresenter extends BasePresenter
{
    private SignInFactory $signInFactory;

    private ForgotPasswordFactory $forgotPasswordFactory;

    public function __construct(SignInFactory $signInFactory,
                                ForgotPasswordFactory $forgotPasswordFactory)
    {
        parent::__construct();

        $this->signInFactory = $signInFactory;
        $this->forgotPasswordFactory = $forgotPasswordFactory;
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
            if ($user instanceof Employee) {
                $user->setAuthToken(null);
                $this->entityManager->flush($user);
            }

            $this->getUser()->logout(true);
        }

        $this->redirect("Sign:in");
    }

    public function actionForgotPassword(): void
    {
        $this->getPageInfo()->title = 'Zapomenuté heslo';
    }

    public function createComponentSignIn(): SignIn
    {
        $control = $this->signInFactory->create();
        $control->onSuccess[] = function () {
            $this->redirect('Homepage:');
        };

        return $control;
    }

    public function createComponentForgotPassword(): ForgotPassword
    {
        return $this->forgotPasswordFactory->create();
    }
}