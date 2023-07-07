<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;

class OptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Option::class;
    }

    public function index(AdminContext $context): KeyValueStore|Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $response = parent::index($context);

        if ($response instanceof Response) {
            return $response;
        }

        /** @var EntityCollection $entities */
        $entities = $response->get('entities');

        foreach ($entities as $entity) {
            $fields = $entity->getFields();

            $valueField = $fields->getByProperty('value');
            $typeField = $fields->getByProperty('type');

            $type = $typeField->getValue();

            $valueField->setFormType($type);

            $entity->getFields()->unset($typeField);
        }

        $response->set('entities', $entities);

        return $response;
    }

    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        $formBuilder = parent::createEditForm($entityDto, $formOptions, $context);
        $viewData = $formBuilder->getViewData();
        $value = $viewData->getValue();
    

        $formBuilder->add('value', $viewData->getType(),[
            'data'=> $viewData->getType()===CheckboxType::class ? boolval($value) : $value,
            'label' => 'valeur',
            'required'=>false
        ]);
        return $formBuilder;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance):void
    {
        $option = $entityInstance;

        if($option->getType()=== CheckboxType::class) {
            $value = $option->getValue() ? '1' : 0;
            $option->setValue($value);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(null)
            ->setEntityLabelInPlural('Réglages généraux')
            ->showEntityActionsInlined();
    }


    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label', 'Option')
                ->setFormTypeOption('attr', [
                    'readonly' => true
                ])
                ->setSortable(false),
            TextField::new('value'),
            HiddenField::new('type'),

        ];
    }
    
}
