<?php

declare(strict_types=1);

namespace App\Components\Customer\Form;

use App\Core;
use App\Database\Entity;
use App\Events\Customer;
use Doctrine\ORM;
use Nette\Application\UI;
use Nette\Forms;
use Nette\Http;
use Nette\Utils;
use Symfony\Component\EventDispatcher;

final class Form extends Core\UI\CoreControl
{
    /** @var array<callable(): void> */
    public array $onCreate = [];

    /** @var array<callable(): void> */
    public array $onEdit = [];

    /** @var array<callable(): void> */
    public array $onArchived = [];

    /** @var array<callable(): void> */
    public array $onCancelArchived = [];

    private EventDispatcher\EventDispatcherInterface $eventDispatcher;

    private ?Entity\Customer $customer;

    public function __construct(
        EventDispatcher\EventDispatcherInterface $eventDispatcher,
        Entity\EntityManager $entityManager,
        ?int $customerId = null
    ) {
        $this->eventDispatcher = $eventDispatcher;

        if ($customerId !== null) {
            $entity = $entityManager->getCustomerRepository()->find($customerId);

            if (!$entity instanceof Entity\Customer) {
                $this->error('Customer not found');
            }

            $this->customer = $entity;
        } else {
            $this->customer = null;
        }
    }

    public function beforeRender(): void
    {
        if ($this->customer instanceof Entity\Customer) {
            $form = $this['customerForm'];
            assert($form instanceof UI\Form);

            $form->setDefaults([
                'name' => $this->customer->getName()
            ]);

            if ($this->customer->isArchived()) {
                foreach ($form->getControls() as $input) {
                    if (!($input instanceof Forms\Controls\TextBase)) {
                        continue;
                    }

                    $input->setHtmlAttribute('readonly');
                }

                $this->template->isArchived = true;
            }
        }

        $this->template->isEditMode = $this->customer instanceof Entity\Customer;
    }

    public function createComponentCustomerForm(): UI\Form
    {
        $form = new UI\Form();

        $form->addText('name', 'Jméno')
            ->setRequired('%label musí být zadáné');

        $form->onValidate[] = function () {
            if ($this->customer === null || !$this->customer->isArchived()) {
                return;
            }

            $this->error('Note is archived', Http\IResponse::S403_FORBIDDEN);
        };

        if ($this->customer instanceof Entity\Customer) {
            $form->addSubmit('process', 'Upravit zákazníka');
            $form->onSuccess[] = [$this, 'editCustomer'];
        } else {
            $form->addSubmit('process', 'Vytvořit zákazníka');
            $form->onSuccess[] = [$this, 'addCustomer'];
        }

        return $form;
    }

    public function addCustomer(UI\Form $form, Data $values): void
    {
        $event = new Customer\AddCustomerEvent($values->name);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onCreate);
    }

    public function editCustomer(UI\Form $form, Data $values): void
    {
        $customer = $this->customer;
        if (!$customer instanceof Entity\Customer) {
            throw ORM\EntityNotFoundException::fromClassNameAndIdentifier(Entity\Customer::class, []);
        }

        $event = new Customer\EditCustomerEvent($customer->getId(), $values->name);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onEdit);
    }

    public function handleArchive(): void
    {
        $customer = $this->customer;
        if (!$customer instanceof Entity\Customer) {
            throw ORM\EntityNotFoundException::fromClassNameAndIdentifier(Entity\Customer::class, []);
        }

        $event = new Customer\EditCustomerEvent($customer->getId(), null, true);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onArchived);
    }

    public function handleCancelArchive(): void
    {
        $customer = $this->customer;
        if (!$customer instanceof Entity\Customer) {
            throw ORM\EntityNotFoundException::fromClassNameAndIdentifier(Entity\Customer::class, []);
        }

        $event = new Customer\EditCustomerEvent($customer->getId(), null, false);
        $this->eventDispatcher->dispatch($event);
        Utils\Arrays::invoke($this->onCancelArchived);
    }
}
