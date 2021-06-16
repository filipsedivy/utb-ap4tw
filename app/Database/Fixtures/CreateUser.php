<?php

declare(strict_types=1);

namespace App\Database\Fixtures;

use App\Database\Entity\Employee;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nette\DI\Container;
use Nette\Security\Passwords;
use Nettrine\Fixtures\ContainerAwareInterface;

class CreateUser implements FixtureInterface, ContainerAwareInterface
{
    private Container $container;

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager): void
    {
        $passwords = $this->container->getByType(Passwords::class);

        $employee = new Employee(
            'admin',
            $passwords->hash('admin'),
            'admin@localhost.dev',
            'Administrátor'
        );

        $manager->persist($employee);
        $manager->flush();
    }
}
