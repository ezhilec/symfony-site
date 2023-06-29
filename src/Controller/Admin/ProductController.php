<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private ImageService $imageService
    ) {
    }
    
    #[Route('/', name: 'app_admin_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $this->imageService->addImages($product, $images, $this->getParameter('upload_directory'));
            
            $productRepository->save($product, true);
            
            return $this->redirectToRoute('app_admin_product_edit', ['id' => $product->getId()]);
        }
        
        return $this->render('admin/product/new.html.twig', [
            'form' => $form->createView(),
            'maxFilesize' => 2,
            'maxFiles' => 5,
        ]);
    }
    
    #[Route('/{id}', name: 'app_admin_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imagesToDeleteIds = explode(',', $request->request->get('images_to_delete'));
            $this->imageService->deleteImages($imagesToDeleteIds, $this->getParameter('upload_directory'));
            
            $images = $form->get('images')->getData();
            $this->imageService->addImages($product, $images, $this->getParameter('upload_directory'));
            
            $productRepository->save($product, true);
            
            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $existingFiles = [];
        foreach ($product->getImages() as $image) {
            $file = [
                'id' => $image->getId(),
                'name' => $image->getUrl(),
                'url' => '/uploads/photos/' . ($image->getPreviewUrl() ?? $image->getUrl()),
            ];
            $existingFiles[] = $file;
        }
        
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'maxFilesize' => 2,
            'maxFiles' => 5,
            'existingFiles' => $existingFiles
        ]);
    }
    
    #[Route('/{id}', name: 'app_admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }
        
        return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
