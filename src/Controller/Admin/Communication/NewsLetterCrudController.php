<?php

namespace App\Controller\Admin\Communication;

use App\Entity\Communication\NewsLetter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NewsLetterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsLetter::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Newsletter')
            ->setEntityLabelInPlural('Newsletters')
            ->setPageTitle('index', 'Administration des Newsletters')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined()
            ->setSearchFields(['id', 'email'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setLabel('Nom'),
            TextField::new('title')
                ->setLabel('Titre'),
            TextareaField::new('summary')
                ->setLabel('Résumé'),
            ImageField::new('image', 'Image')
                ->setBasePath('/images/newsletter')
                ->setUploadDir('public/images/newsletter')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setLabel('Image'),
            TextEditorField::new('description')
                ->hideOnIndex()
                ->setFormType(CKEditorType::class),
            DateField::new('createdAt')
                ->setLabel('Date de création')
                ->setFormat('long')
                ->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $previewNewsletter = Action::new('previewNewsletter', 'Prévisualiser', 'fa fa-eye')
            ->addCssClass('btn btn-info')
            ->linkToRoute('newsletter_preview', function (NewsLetter $newsLetter) {
                return [
                    'id' => $newsLetter->getId(),
                ];
            })
            ->displayIf(function (NewsLetter $newsLetter) {
                return $newsLetter->getId() !== null;
            });
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
            ->add('detail', $previewNewsletter)
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
