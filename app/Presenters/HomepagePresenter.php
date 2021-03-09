<?php

declare(strict_types=1);

namespace App\Presenters;


final class HomepagePresenter extends AuthPresenter
{
    public function actionDefault(): void
    {
        $this->getPageInfo()->title = 'Nástěnka';
    }
}
