<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title','titre'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('content','contenue'),
            TextField::new('featuredText','résumé du blog'),
            DateTimeField::new('createdAt','date de création')->hideOnForm(),
            DateTimeField::new('updatedAt','date de modification')->hideOnForm(),
        ];
    }
   
}
