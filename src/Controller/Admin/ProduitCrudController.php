<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description')->onlyOnForms(),
            ImageField::new('photo')->setBasePath('images/produit')->setUploadDir('public/images/produit')->setUploadedFileNamePattern('[slug]-[timestamp],[extension]'),
            ChoiceField::new('taille')
            ->setChoices(['S' => 'S', 'M' => 'M', 'L' => 'L'])
            ->setLabel('Taille'),
            ChoiceField::new('couleur')
            ->setChoices(['Rouge' => 'Rouge', 'Bleu' => 'Bleu', 'Vert' => 'Vert', 'Jaune' => 'Jaune', 'Blanc'=>'Blanc'])
            ->setLabel('Couleur'),
            ChoiceField::new('collection')
            ->setChoices(['Homme' => 'Homme', 'Femme' => 'Femme', "Ni l'un ni l'autre" => "Ni l'un ni l'autre"])
            ->setLabel('Collection'),
            IntegerField::new('prix'),
            IntegerField::new('stock'),
            DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:M')->hideOnForm(),
        ];
    }

            
    public function createEntity(string $entityFqcn){
        $produit = new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime);
        return $produit;
        
    }
    
}
