<?php

namespace App\Controller\Frontend;

use App\Repository\PageRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        private PageRepository $pageRepository,
        private LoggerInterface $logger
    ) {}
    
    #[Route('/{slug}', name: 'app_page_show', defaults: ['slug' => ''], methods: ['GET', 'HEAD'], priority: 0)]
    public function index(string $slug): Response
    {
        $this->logger->info('qwe');
        $page = $this->pageRepository->findOneBy(['slug' => $slug]);
        
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }
        
        return $this->render('frontend/page/index.html.twig', [
            'page' => $page,
        ]);
    }
}
