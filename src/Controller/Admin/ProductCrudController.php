<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureToCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setPageTitle('index', 'Administration des Produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(10)
            ->setSearchFields(['id', 'name', 'description', 'price', 'quantity', 'createdAt', 'updatedAt']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        AssociationField::new('category', 'Catégorie')
            ->setLabel('Catégorie'),
        TextField::new('name')
            ->setLabel('Nom'),
        TextareaField::new('summary', 'Résumé')
            ->setLabel('Résumé'),
        TextareaField::new('description', 'Description')
            ->setLabel('Description'),
        ImageField::new('image0', 'Image Principale')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ImageField::new('image1', 'Image 1')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ImageField::new('image2', 'Image 2')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ImageField::new('image3', 'Image 3')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ImageField::new('image4', 'Image 4')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        MoneyField::new('price')
            ->setLabel('Prix')
            ->setCurrency('EUR'),
        NumberField::new('quantity')
            ->setLabel('Quantité'),
        DateTimeField::new('createdAt')
            ->setLabel('Créé le')
            ->setFormTypeOptions([
                'data' => new DateTime(),
            ]),
        DateTimeField::new('updatedAt')
            ->setLabel('Modifié le')
            ->setFormTypeOptions([
                'data' => new DateTime(),
            ]),
        DateTimeField::new('releaseAt')
            ->setLabel('Sorti le')
            ->setFormat('dd-MM-Y HH:mm')
            ->setTimezone('Europe/Paris'),
        NumberField::new('weight')
            ->setLabel('Poids')
            ->setHelp('Poids en grammes'),
        ];
    }
}