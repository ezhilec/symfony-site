<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\ManyToOne(targetEntity: ProductCategory::class, fetch: 'EAGER', inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference_number = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->reference_number;
    }

    public function setReferenceNumber(?string $reference_number): self
    {
        $this->reference_number = $reference_number;

        return $this;
    }
    
    public function getImages(): Collection
    {
        return $this->images;
    }
    
    public function addImage(ProductImage $image): self
    {
        $image->setProduct($this);
        $this->images->add($image);
        
        return $this;
    }
    
    public function removeImage(Image $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }
}
