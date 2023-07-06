<?php

namespace App\Twig;


use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    const ADMIN_NAMESPACE = 'App\Controller\Admin';

    public function __construct(
        private RouterInterface $router,
        private AdminUrlGenerator $adminUrlGenerator,
    ) {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_admin_url', [$this, 'getAdminUrl']),
        ];
    }
    

    public function getFilters(): array
    {
        return [
            new TwigFilter('menuLink', [$this, 'menuLink']),
        ];
    }

    public function menuLink(Menu $menu): string
    {
    
        $url = $menu->getLink() ?: '#';

        if ($url !== '#') {
            return $url;
        }

        $page = $menu->getPage();

        if ($page) {
            $name = 'app_page_show';
            $slug = $page->getSlug();
        }

        $article = $menu->getArticle();

        if ($article) {
            $name = 'app_article_show';
            $slug = $article->getSlug();
        }

        $category = $menu->getCategory();

        if ($category) {
            $name = 'app_category_show';
            $slug = $category->getSlug();
        }

        return $this->router->generate($name, [
            'slug' => $slug
        ]);
    }


    public function getAdminUrl(string $controller, string $action = Action::INDEX): string
    {
        return $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . '\\' . $controller)
            ->setAction($action)
            ->generateUrl();
    }

}