<?php

declare(strict_types = 1);

namespace App\Components\Employee\Grid;

interface GridFactory
{

	public function create(): Grid;

}
