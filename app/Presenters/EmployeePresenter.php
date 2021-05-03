<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components\Employee;
use App\Database;
use function assert;

final class EmployeePresenter extends AuthPresenter
{

	private Employee\Grid\GridFactory $gridFactory;

	private ?Database\Entity\Employee $cursor = null;

	public function __construct(Employee\Grid\GridFactory $gridFactory)
	{
		parent::__construct();

		$this->gridFactory = $gridFactory;
	}

	public function renderDefault(): void
	{
		$this->pageInfo->title = 'Zaměstnanci';
	}

	public function renderAdd(): void
	{
		$this->pageInfo->title = 'Přidat zaměstnance';
		$this->pageInfo->backlink = $this->link('default');
	}

	public function actionEdit(int $id): void
	{
		$entity = $this->checkOneById(Database\Entity\Employee::class, $id);
		assert($entity instanceof Database\Entity\Employee);

		$this->cursor = $entity;
	}

	public function renderEdit(): void
	{
		\assert($this->cursor instanceof Database\Entity\Employee);

		$this->pageInfo->title = 'Upravit zaměstnance';
		$this->pageInfo->subtitle = $this->cursor->name;
		$this->pageInfo->backlink = $this->link('default');
	}

	public function createComponentGrid(): Employee\Grid\Grid
	{
		return $this->gridFactory->create();
	}

}
