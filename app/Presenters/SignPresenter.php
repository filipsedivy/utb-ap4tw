<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\SignIn\SignIn;
use App\Components\SignIn\SignInFactory;

final class SignPresenter extends BasePresenter
{
    private SignInFactory $signInFactory;

    public function __construct(SignInFactory $signInFactory)
    {
        parent::__construct();

        $this->signInFactory = $signInFactory;
    }

    public function createComponentSignIn(): SignIn
    {
        return $this->signInFactory->create();
    }
}