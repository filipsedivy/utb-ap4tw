<?php declare(strict_types=1);

namespace App\Components\FormCustomer;

use App\Core\UI\CoreControl;
use App\Database\Entity\Customer;
use App\Database\Entity\EntityManager;
use App\Events\Customer\AddCustomerEvent;
use App\Events\Customer\EditCustomerEvent;
use Nette\Application\UI;
use Nette\Forms\Controls\TextBase;
use Nette\Http\IResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method void onCreate()
 * @method void onEdit()
 * @method void onArchived()
 */
final class FormCustomer extends CoreControl
{
    /** @var callable[] */
    public array $onCreate = [];

    /** @var callable[] */
    public array $onEdit = [];

    /** @var callable[] */
    public array $onArchived = [];

    private EventDispatcherInterface $eventDispatcher;

    private ?Customer $customer;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                EntityManager $entityManager,
                                ?int $customerId = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        if ($customerId !== null) {
            $entity = $entityManager->getCustomerRepository()->find($customerId);

            if (!$entity instanceof Customer) {
                $this->error('Customer not found');
            }

            $this->customer = $entity;
        } else {
            $this->customer = null;
        }
    }

    public function beforeRender(): void
    {
        if ($this->customer instanceof Customer) {
            $form = $this['customerForm'];
            assert($form instanceof UI\Form);

            $form->setDefaults([
                'name' => $this->customer->getName()
            ]);

            if ($this->customer->isArchived()) {
                foreach ($form->getControls() as $input) {
                    if ($input instanceof TextBase) {
                        $input->setHtmlAttribute('readonly');
                    }
                }

                $this->template->isArchived = true;
            }
        }

        $this->template->isEditMode = $this->customer instanceof Customer;
    }

    public function createComponentCustomerForm(): UI\Form
    {
        $form = new UI\Form();

        $form->addText('name', 'Jméno')
            ->setRequired('%label musí být zadáné');

        $form->onValidate[] = function () {
            if ($this->customer->isArchived()) {
                $this->error('Note is archived', IResponse::S403_FORBIDDEN);
            }
        };

        if ($this->customer instanceof Customer) {
            $form->addSubmit('process', 'Upravit zákazníka');
            $form->onSuccess[] = [$this, 'editCustomer'];
        } else {
            $form->addSubmit('process', 'Vytvořit zákazníka');
            $form->onSuccess[] = [$this, 'addCustomer'];
        }

        return $form;
    }

    public function addCustomer(UI\Form $form, FormData $values): void
    {
        $event = new AddCustomerEvent($values->name);
        $this->eventDispatcher->dispatch($event);
        $this->onCreate();
    }

    public function editCustomer(UI\Form $form, FormData $values): void
    {
        $event = new EditCustomerEvent($this->customer->getId(), $values->name);
        $this->eventDispatcher->dispatch($event);
        $this->onEdit();
    }

    public function handleArchive(): void
    {
        $event = new EditCustomerEvent($this->customer->getId(), null, true);
        $this->eventDispatcher->dispatch($event);
        $this->onArchived();
    }
}