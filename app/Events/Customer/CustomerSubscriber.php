<?php

declare(strict_types=1);

namespace App\Events\Customer;

use App\Database\Entity\Customer;
use App\Database\Entity\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CustomerSubscriber implements EventSubscriberInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AddCustomerEvent::class => 'addCustomer',
            EditCustomerEvent::class => 'editCustomer'
        ];
    }

    public function addCustomer(AddCustomerEvent $event): void
    {
        $entity = new Customer();
        $entity->setName($event->getName());
        $this->entityManager->persist($entity);
    }

    public function editCustomer(EditCustomerEvent $event): void
    {
        $entity = $this->entityManager->getCustomerRepository()->find($event->getCustomerId());

        if (!$entity instanceof Customer) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Customer::class, [(string)$event->getCustomerId()]);
        }

        if ($event->getName() !== null) {
            $entity->setName($event->getName());
        }

        if ($event->getArchive() !== null) {
            $entity->setArchived($event->getArchive());
        }
    }
}
