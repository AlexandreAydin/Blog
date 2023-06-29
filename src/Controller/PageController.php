<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'app_page_show')]
    public function show(): Response
    {
        return $this->render('pages/page/show.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
