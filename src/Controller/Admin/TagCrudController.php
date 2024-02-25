<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\TagRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TagCrudController extends AbstractCrudController
{
    public function __construct(
        private TagRepository $tagRepository
    ) {}
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('tag')->setFormType(EntityType::class)->setFormTypeOption('class', Tag::class),
        ];
    }
   
}
