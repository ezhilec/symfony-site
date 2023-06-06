<?php

namespace App\Twig\Runtime;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class GlobalVariableExtensionRuntime implements RuntimeExtensionInterface
{
    
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}
    
    public function getMenuPages(): array
    {
        $repository = $this->entityManager->getRepository(Page::class);
        return $repository->findBy([], ['priority' => 'DESC']);
    }
}
