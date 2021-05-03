<?php

declare(strict_types = 1);

namespace App\Components\Note\Form;

interface FormFactory
{

	public function create(?int $id = null): Form;

}
