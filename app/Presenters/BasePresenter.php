<?php declare(strict_types=1);

namespace App\Presenters;

use App\Core\Presenter\PageInfo;
use App\Components\Menu\ {
    Menu,
    MenuFactory
};
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    private ?PageInfo $pageInfo = null;

    final public function getPageInfo(): PageInfo
    {
        if ($this->pageInfo === null) {
            $this->pageInfo = new PageInfo();
        }

        return $this->pageInfo;
    }

    public function afterRender(): void
    {
        parent::afterRender();

        $this->template->pageInfo = $this->getPageInfo();
    }
}