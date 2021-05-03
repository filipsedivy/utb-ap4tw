<?php

declare(strict_types=1);

namespace App\Database\Fixtures;

use App\Database\Entity\Customer;
use App\Database\Entity\Employee;
use App\Database\Entity\Note;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Nette\DI\Container;
use Nette\Security\Passwords;
use Nettrine\Fixtures\ContainerAwareInterface;

class SimpleData implements FixtureInterface, ContainerAwareInterface
{
    private const SIMPLE_PASSWORD = 'demo';

    private Container $container;

    private Passwords $passwords;

    private ObjectManager $objectManager;

    private Faker\Generator $faker;

    public function setContainer(Container $container): void
    {
        $this->container = $container;

        $passwords = $container->getByType(Passwords::class);
        assert($passwords instanceof Passwords);
        $this->passwords = $passwords;

        $this->faker = Faker\Factory::create('cs_CZ');
        $this->faker->seed();
    }

    public function load(ObjectManager $manager): void
    {
        $this->objectManager = $manager;
        $this->createSimpleEmployees();
        $this->createSimpleCustomers();
        $this->createSimpleNotes();
        $manager->flush();
    }

    private function createSimpleEmployees(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $password = $this->passwords->hash(self::SIMPLE_PASSWORD);

            $employee = new Employee();
            $employee->setEmail($this->faker->freeEmail);
            $employee->setPassword($password);
            $employee->setName($this->faker->name);
            $employee->setUsername($this->faker->userName);
            $this->objectManager->persist($employee);
        }

        $this->objectManager->flush();
    }

    private function createSimpleCustomers(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $customer = new Customer();

            if ($i % 2 === 0) {
                $customer->setName($this->faker->company);
            } else {
                $customer->setName($this->faker->name);
            }

            $this->objectManager->persist($customer);
        }
    }

    private function createSimpleNotes(): void
    {
        $employees = $this->objectManager->getRepository(Employee::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $employee = $employees[array_rand($employees)];
            assert($employee instanceof Employee);

            $note = new Note();
            $note->setCreator($employee);
            $note->setNote($this->faker->paragraph());

            $this->objectManager->persist($note);
        }
    }
}
