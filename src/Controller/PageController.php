<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        private PageRepository $pageRepository
    ) {}
    
    #[Route('/{slug}', name: 'pages', defaults: ['slug' => ''], methods: ['GET', 'HEAD'], priority: 0)]
    public function index(string $slug): Response
    {
        $page = $this->pageRepository->findOneBy(['slug' => $slug]);
        
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }
        
        return $this->render('front/page/index.html.twig', [
            'page' => $page,
        ]);
    }
    
    #[Route('/catalog', name: 'catalog', methods: ['GET', 'HEAD'], priority: 1)]
    public function catalog(): Response
    {
        dd(123);
    }
}
