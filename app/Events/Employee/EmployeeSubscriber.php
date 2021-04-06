<?php declare(strict_types=1);

namespace App\Events\Employee;

use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Security\Passwords;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EmployeeSubscriber implements EventSubscriberInterface
{
    private EntityManager $entityManager;

    private Passwords $passwords;

    public function __construct(EntityManager $entityManager, Passwords $passwords)
    {
        $this->entityManager = $entityManager;
        $this->passwords = $passwords;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ChangePasswordEvent::class => 'changePassword',
            ChangePersonalDataEvent::class => 'changePersonalData'
        ];
    }

    public function changePassword(ChangePasswordEvent $event): void
    {
        $employee = $this->entityManager->getEmployeeRepository()->find($event->getUser());

        if (!$employee instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [$event->getUser()]);
        }

        $hashPassword = $this->passwords->hash($event->getPassword());
        $employee->setPassword($hashPassword);
    }

    public function changePersonalData(ChangePersonalDataEvent $event): void
    {
        $employee = $this->entityManager->getEmployeeRepository()->find($event->getUser());

        if (!$employee instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [$event->getUser()]);
        }

        $employee->setName($event->getName());
    }
}