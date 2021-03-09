<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\ForgotPassword\ForgotPassword;
use App\Components\ForgotPassword\ForgotPasswordFactory;
use App\Components\SignIn\SignIn;
use App\Components\SignIn\SignInFactory;

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
        $this->getPageInfo()->title = 'Přihlášení';
    }

    public function actionOut(): void
    {
        if ($this->getUser()->isLoggedIn()) {
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