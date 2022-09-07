<?php

namespace App\Controller\Admin;

use App\Entity\Taxe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TaxeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Taxe::class;
    }

    public function configureToCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Taxe')
            ->setEntityLabelInPlural('Taxes')
            ->setPageTitle('index', 'Administration des Taxes')
            ->setSearchFields(['id', 'name'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        TextField::new('name')
            ->setLabel('Nom'),
        PercentField::new('percent')
            ->setLabel('Pourcentage')
            ->setNumDecimals(2),
        ];
    }
}