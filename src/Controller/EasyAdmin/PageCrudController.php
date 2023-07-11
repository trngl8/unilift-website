<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Сторінка')
            ->setEntityLabelInPlural('Сторінки')
            ->showEntityActionsInlined()
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setRequired(false)->hideOnForm(),
            IdField::new('slug')->setRequired(true),
            TextField::new('title'),
            TextareaField::new('description'),
            TextEditorField::new('text')->hideOnIndex(),
            ImageField::new('filename')->setUploadDir('public/upload')->setBasePath('upload')->setRequired(false),
            BooleanField::new('active'),
        ];
    }

}
