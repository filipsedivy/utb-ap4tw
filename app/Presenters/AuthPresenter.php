<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Menu\{
    Menu,
    MenuFactory
};
use Nette\NotImplementedException;

abstract class AuthPresenter extends BasePresenter
{
    private ?MenuFactory $menuFactoryControl = null;

    final public function injectMenuFactoryControl(MenuFactory $menuFactory): void
    {
        $this->menuFactoryControl = $menuFactory;
    }

    final public function createComponentMenu(): Menu
    {
        if ($this->menuFactoryControl === null) {
            throw new NotImplementedException();
        }

        return $this->menuFactoryControl->create();
    }

    /**
     * @param mixed $element
     */
    final public function checkRequirements($element): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:out');
        }

        parent::checkRequirements($element);
    }
}
