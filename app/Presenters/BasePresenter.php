<?php declare(strict_types=1);

namespace App\Presenters;

use App\Core\Presenter\PageInfo;
use App\Database\Entity\EntityManager;
use App\Services\User\IdentityRefresher;
use Doctrine\ORM\EntityNotFoundException;
use App\Components\Menu\ {
    Menu,
    MenuFactory
};
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    public EntityManager $entityManager;

    private ?PageInfo $pageInfo = null;

    private IdentityRefresher $identityRefresher;

    final public function injectEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    final public function injectIdentityRefresher(IdentityRefresher $identityRefresher): void
    {
        $this->identityRefresher = $identityRefresher;
    }

    final public function getPageInfo(): PageInfo
    {
        if ($this->pageInfo === null) {
            $this->pageInfo = new PageInfo();
        }

        return $this->pageInfo;
    }

    public function startup(): void
    {
        parent::startup();

        try {
            $this->identityRefresher->update();
        } catch (EntityNotFoundException $e) {
            $this->user->logout(true);
            $this->flashMessage('Uživatelský účet neexistuje', 'warning');
            $this->redirect('Sign:in');
        }
    }

    public function afterRender(): void
    {
        parent::afterRender();

        $this->template->pageInfo = $this->getPageInfo();
    }
}