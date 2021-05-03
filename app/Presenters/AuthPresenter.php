<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components\Menu\Menu;
use App\Components\Menu\MenuFactory;
use App\Core\Presenter;
use App\Database\Entity\Employee;
use App\Presenters\EntityNotFoundException;
use App\Presenters\LogicException;
use App\Presenters\NotImplementedException;

/** @property-read \App\Database\Entity\Employee $authEmployee */
abstract class AuthPresenter extends BasePresenter
{

    use Presenter\EntityPresenter;

    private ?MenuFactory $menuFactoryControl = null;

    final public function injectMenuFactoryControl(MenuFactory $menuFactory): void
    {
        $this->menuFactoryControl = $menuFactory;
    }

    final public function createComponentMenu(): Menu
    {
        if (null === $this->menuFactoryControl) {
            throw new \App\Presenters\NotImplementedException;
        }

        return $this->menuFactoryControl->create();
    }

    /** @param mixed $element */
    final public function checkRequirements($element): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:out');
        }

        parent::checkRequirements($element);
    }

    final public function getAuthEmployee(): Employee
    {
        if (!$this->user->loggedIn) {
            throw new \App\Presenters\LogicException('User is not logged');
        }

        $entity = $this->entityManager->getEmployeeRepository()->find($this->user->id);

        if (!$entity instanceof Employee) {
            throw \App\Presenters\EntityNotFoundException::fromClassNameAndIdentifier(
                Employee::class,
                [(string)$this->user->id],
            );
        }

        return $entity;
    }

}
