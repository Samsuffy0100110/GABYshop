<?php

namespace App\Controller\Admin\Shop;

use DateTime;
use App\Entity\Front\Banner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BannerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Banner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInPlural('Bannières')
            ->setEntityLabelInSingular('Bannière')
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Administration des Banniéres');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setLabel('Nom'),
            ImageField::new('image')
                ->setBasePath('/images/banners/')
                ->setUploadDir('public/images/banners/')
                ->setUploadedFileNamePattern('[name].[extension]')
                ->setLabel('Banniére')
                ->setHelp('La banniére doit être au format jpg, jpeg, png ou gif et doit faire moins de 2Mo'),
            ChoiceField::new('position')
                ->setLabel('Position')
                ->setHelp('La position de la banniére sur la page, 
                banniére du haut = carousel et banniére du bas = banniére fixe')
                ->setChoices([
                    'Banniére Carousel' => '1',
                    'Banniére Fixe' => '2',
                ])
                ->setLabel('Position'),
            DateTimeField::new('createdAt')
                ->setFormTypeOptions([
                    'data' => new DateTime(),
                ])
                ->setLabel('Date de création')
                ->setFormat('long'),
            DateTimeField::new('startedAt')
                ->setLabel('Date de début d\'affichage')
                ->setFormat('long')
                ->setTimezone('Europe/Paris'),
            DateTimeField::new('endedAt')
                ->setLabel('Date de fin d\'affichage')
                ->setFormat('long')
                ->setTimezone('Europe/Paris'),
            BooleanField::new('isActive')
                ->setLabel('Actif'),
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
