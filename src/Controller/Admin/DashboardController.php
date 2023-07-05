<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Menu;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {   
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog');
    }

    public function configureMenuItems(): iterable
    {
     
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-home', 'app_home');

        if($this->isGranted('ROLE_ADMIN')){ 
            yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class)
                    ->setQueryParameter('submenuIndex', 0),
                MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Menu::class)
                    ->setQueryParameter('submenuIndex', 1),
                MenuItem::linkToCrud('Liens personnalisés', 'fas fa-link', Menu::class)
                    ->setQueryParameter('submenuIndex', 2),
                MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class)
                    ->setQueryParameter('submenuIndex', 3),
            ]);

        }

        if($this->isGranted('ROLE_AUTHOR')){
            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Tous les articles', 'fas fa-newspaper',Article::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus',Article::class)->setAction(Crud::PAGE_NEW),
            ]);


            yield MenuItem::subMenu('Médias', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Médiathèque', 'fas fa-photo-video',Media::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus',Media::class)->setAction(Crud::PAGE_NEW),
            ]);
        }

        if($this->isGranted('ROLE_ADMIN')){ 
            yield MenuItem::subMenu('Compte', 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud('Tous les comptes', 'fas fa-user-friends', User::class),
                MenuItem::linkToCrud('Articles', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            ]);
        }

    }
}
