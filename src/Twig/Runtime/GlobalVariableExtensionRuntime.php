<?php

namespace App\Twig\Runtime;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalVariableExtensionRuntime implements RuntimeExtensionInterface
{
    
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag
    ) {}
    
    public function getMenuPages(): array
    {
        $repository = $this->entityManager->getRepository(Page::class);
        return $repository->findBy([], ['priority' => 'DESC']);
    }
    
    public function getAdminPages(): array
    {
        return require __DIR__.'/../../../config/admin_pages.php';
    }
    
    public function getUploadsDir(): string
    {
        return str_replace("app/public/", "", $this->parameterBag->get('photo_dir')) . "/";
    }
}
