<?php

namespace App\Controller\Admin\Product;

use DateTime;
use App\Entity\Product\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setPageTitle('index', 'Administration des Produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined()
            ->setSearchFields(['id', 'name', 'description', 'price', 'createdAt', 'updatedAt']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('category', 'Catégorie')
            ->setCrudController(CategoryCrudController::class)
            ->setFormTypeOption('choice_label', function ($category) {
                return $category->getParent()->getName() . ' > ' . $category->getName();
            }),
        TextField::new('name')
            ->setLabel('Nom'),
        TextareaField::new('summary', 'Résumé')
            ->setLabel('Résumé'),
        TextareaField::new('description', 'Description')
            ->setLabel('Description')
            ->hideOnIndex(),
        CollectionField::new('attributs')
            ->useEntryCrudForm(),
        ImageField::new('image0', 'Image Principale')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ImageField::new('image1', 'Image 1')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->hideOnIndex(),
        ImageField::new('image2', 'Image 2')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->hideOnIndex(),
        ImageField::new('image3', 'Image 3')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->hideOnIndex(),
        ImageField::new('image4', 'Image 4')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->hideOnIndex(),
        MoneyField::new('price')
            ->setLabel('Prix')
            ->setCurrency('EUR'),
        AssociationField::new('taxe', 'TVA')
            ->setLabel('TVA'),
        DateField::new('createdAt')
            ->setFormat('long')
            ->hideOnForm(),
        DateTimeField::new('updatedAt')
            ->setLabel('Modifié le')
            ->setFormat('long')
            ->setFormTypeOptions([
                'data' => new DateTime(),
            ])
            ->setTimezone('Europe/Paris'),
        DateTimeField::new('releaseAt')
            ->setLabel('Sorti le')
            ->setFormat('long')
            ->setTimezone('Europe/Paris'),
        NumberField::new('weight')
            ->setLabel('Poids')
            ->setHelp('Poids en grammes'),
        SlugField::new('slug')
            ->setTargetFieldName('name')
            ->setLabel('Slug')
            ->setHelp('Le slug est le nom qui apparaîtra dans la barre de navigation, 
            il est généré automatiquement à partir du nom du produit')
            ->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, 'detail')
            ->update(Crud::PAGE_INDEX, 'detail', function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('voir')->setCssClass('text-info');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('modifier')->addCssClass('text-warning');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('supprimer')
                    ->setCssClass('btn btn-danger');
            })
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('modifier')->addCssClass('btn btn-warning');
            })
            ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) {
                return $action->setIcon('fa fa-arrow-left')->setLabel('retour');
            });
    }
}
