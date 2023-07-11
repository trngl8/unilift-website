<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Topic;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class TopicCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Задачa')
            ->setEntityLabelInPlural('Задачі')
            ->showEntityActionsInlined()
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')
                ->setChoices(['new' => 'new', 'in progress' => 'in progress', 'done' => 'done'])
            )

            ->add('publishedAt')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setRequired(false)->hideOnForm(),
            TextField::new('title'),
            //ChoiceField::new('status'),
            TextEditorField::new('description'),
            TextField::new('type'),
            DateField::new('publishedAt')
        ];
    }

}
