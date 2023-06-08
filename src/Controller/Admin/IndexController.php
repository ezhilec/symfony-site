<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'app_admin_index', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
