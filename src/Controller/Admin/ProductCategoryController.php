<?php

namespace App\Controller\Admin;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product_category')]
class ProductCategoryController extends AbstractController
{
    #[Route('/', name: 'app_admin_product_category_index', methods: ['GET'])]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('admin/product_category/index.html.twig', [
            'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_admin_product_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductCategoryRepository $productCategoryRepository): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // todo replace to service
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('files.product_category_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $productCategory->setImage($newFilename);
            }
            
            $productCategoryRepository->save($productCategory, true);
            
            return $this->redirectToRoute('app_admin_product_category_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('admin/product_category/new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_admin_product_category_show', methods: ['GET'])]
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('admin/product_category/show.html.twig', [
            'product_category' => $productCategory,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_admin_product_category_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ProductCategory $productCategory,
        ProductCategoryRepository $productCategoryRepository
    ): Response {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                
                try {
                    $imageFile->move($this->getParameter('upload_directory'), $newFilename);
                    $productCategory->setImage($newFilename);
                } catch (FileException $e) {
                }
            }
            
            $productCategoryRepository->save($productCategory, true);
            
            return $this->redirectToRoute('app_admin_product_category_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('admin/product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_admin_product_category_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        ProductCategory $productCategory,
        ProductCategoryRepository $productCategoryRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $productCategory->getId(), $request->request->get('_token'))) {
            $productCategoryRepository->remove($productCategory, true);
        }
        
        return $this->redirectToRoute('app_admin_product_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
