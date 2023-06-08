<?php

namespace App\EntityListener;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: ProductCategory::class)]
#[AsEntityListener(event: Events::preUpdate, entity: ProductCategory::class)]
class ProductCategoryEntityListener
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }
    
    public function prePersist(ProductCategory $productCategory, LifecycleEventArgs $event): void
    {
        $productCategory->setSlugIfEmpty($this->slugger);
    }
    
    public function preUpdate(ProductCategory $productCategory, LifecycleEventArgs $event): void
    {
        $productCategory->setSlugIfEmpty($this->slugger);
    }
}