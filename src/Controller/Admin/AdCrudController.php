<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AdCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ad::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre'),
            TextareaField::new('description'),
        ];
    }
}
