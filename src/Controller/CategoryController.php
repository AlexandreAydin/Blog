<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category_show')]
    public function show(?Category $category, ArticleService $articleService): Response
    {

        if(!$category){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('category/show.html.twig', [
            'articles'=>$articleService->getPaginatedArticles($category),
            'category'=> $category
        ]);
    }
}
