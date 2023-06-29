<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class ProductImage extends Image
{
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "images")]
    #[ORM\JoinColumn(name: "imageable_id", referencedColumnName: "id", nullable: false)]
    private Product $product;
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
}