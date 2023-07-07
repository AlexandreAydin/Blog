<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Option;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    public const BLOG_ARTICLE_LIMIT = 'blog_article_limit';

    public function __construct(
        private RequestStack $requestStack,
        private ArticleRepository $articleRepo,
        private OptionService $optionService,
        private PaginatorInterface $paginator
    ) {

    }

    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $articlesQuery = $this->articleRepo->findForPagination($category);
        $page = $request->query->getInt('page', 1);
        $limit = $this->optionService->getValue(ArticleService::BLOG_ARTICLE_LIMIT);



        return $this->paginator->paginate($articlesQuery, $page, $limit);
    }
}