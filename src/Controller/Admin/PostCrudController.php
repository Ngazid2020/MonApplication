<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            AssociationField::new('category'),
            AssociationField::new('user')->hideOnForm(),
            ImageField::new('image','imageFile')
                ->setBasePath('/uploads/images/posts')
                ->setUploadDir('public/uploads/images/posts')
                // ->setCssClass('img-thumbnail img-fluid'),
        ];
    }

    public function configureAssets(Assets $assets): Assets
    {
        
        return $assets
            ->addCssFile('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')
            ->addCssFile('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')
            ->addCssFile('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')
            ->addJsFile('plugins/datatables/jquery.dataTables.min.js')
            ->addJsFile('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')
            ->addJsFile('plugins/datatables-responsive/js/dataTables.responsive.min.js')
            ->addJsFile('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')
            ->addJsFile('plugins/datatables-buttons/js/dataTables.buttons.min.js')
            ->addJsFile('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')
            ->addJsFile('plugins/jszip/jszip.min.js')
            ->addJsFile('plugins/pdfmake/pdfmake.min.js')
            ->addJsFile('plugins/pdfmake/vfs_fonts.js')
            ->addJsFile('plugins/datatables-buttons/js/buttons.html5.min.js')
            ->addJsFile('plugins/datatables-buttons/js/buttons.print.min.js')
            ->addJsFile('plugins/datatables-buttons/js/buttons.colVis.min.js')
            
            // ->addCssFile('build/admin.css')
            // ->addCssFile('https://example.org/css/admin2.css')
            // ->addJsFile('build/admin.js')
            // ->addJsFile('https://example.org/js/admin2.js')

            // // use these generic methods to add any code before </head> or </body>
            // // the contents are included "as is" in the rendered page (without escaping them)
            // ->addHtmlContentToHead('<link rel="dns-prefetch" href="https://assets.example.com">')
            // ->addHtmlContentToBody('<script> ... </script>')
            // ->addHtmlContentToBody('<!-- generated at ' . time() . ' -->')
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->overrideTemplate('crud/index', 'bundles/EasyAdminBundle/custom/crud/index.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des posts');


            //Si on veut configurer plusieurs templates (et aussi optimiser son code), o peut utiliser:
            /* ->overrideTemplates([
            'crud/field/text' => 'admin/product/field_id.html.twig',
            'label/null' => 'admin/labels/null_product.html.twig',
        ]); */
    }
    
    public function configureActions(Actions $actions):Actions
    {
        return $actions
            ->setPermission(Action::DELETE,'ROLE_ADMIN')
        ;
    }
}
