<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Core\Presenter\PageInfo;
use App\Database\Entity\EntityManager;
use Nette\Application\UI\Presenter;

/** @property-read \App\Core\Presenter\PageInfo $pageInfo */
abstract class BasePresenter extends Presenter
{

	public EntityManager $entityManager;

	private ?PageInfo $pageInfoEntity = null;

	final public function injectEntityManager(EntityManager $entityManager): void
	{
		$this->entityManager = $entityManager;
	}

	final public function getPageInfo(): PageInfo
	{
		if ($this->pageInfoEntity === null) {
			$this->pageInfoEntity = new PageInfo();
		}

		return $this->pageInfoEntity;
	}

	public function afterRender(): void
	{
		parent::afterRender();

		$this->template->pageInfo = $this->getPageInfo();
	}

}
