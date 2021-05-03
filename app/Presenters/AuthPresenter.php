<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Database\Entity\Employee;
use Doctrine\ORM\EntityNotFoundException;
use App\Components\Menu\Menu;
use App\Components\Menu\MenuFactory;
use Nette\NotImplementedException;
use App\Core\Presenter;

/**
 * @property-read Employee $authEmployee
 */
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
        if ($this->menuFactoryControl === null) {
            throw new NotImplementedException();
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
            throw new \LogicException('User is not logged');
        }

        $entity = $this->entityManager->getEmployeeRepository()->find($this->user->id);

        if (!$entity instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [(string)$this->user->id]);
        }

        return $entity;
    }
}
