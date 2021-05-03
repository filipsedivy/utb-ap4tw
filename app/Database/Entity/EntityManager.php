<?php

declare(strict_types = 1);

namespace App\Database\Entity;

use App\Database\Repository\TRepositories;
use Nettrine\ORM\EntityManagerDecorator;

final class EntityManager extends EntityManagerDecorator
{

	use TRepositories;

}
