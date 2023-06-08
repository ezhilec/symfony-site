<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $slug = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function setSlugIfEmpty(SluggerInterface $slugger): void
    {
        if (!$this->getSlug()) {
            $slug = $slugger->slug($this->getName())->lower();
            $this->setSlug($slug);
        }
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
    
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    public function setImage(?string $image): self
    {
        $this->image = $image;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
}
