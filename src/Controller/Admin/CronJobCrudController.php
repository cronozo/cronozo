<?php

namespace App\Controller\Admin;

use App\Entity\CronJob;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

final class CronJobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CronJob::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
