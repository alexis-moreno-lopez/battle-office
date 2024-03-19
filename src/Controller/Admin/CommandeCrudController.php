<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Integer;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('adress'),
            TextField::new('adressSup'),
            TextField::new('city'),
            IntegerField::new('cp'),
            TextField::new('country'),
            IntegerField::new('phone'),
            TextField::new('email'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
            DateTimeField::new('deletedAt'),
           AssociationField::new('product'),
           AssociationField::new('deliveryLocations'),
        ];
    }
    
}
