<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "imageable_type", type: "string")]
#[ORM\DiscriminatorMap([
    "product" => ProductImage::class,
])]
#[ORM\Table(name: "image")]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preview_url = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(name: "imageable_id", type: "string")]
    private $imageable_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPreviewUrl(): ?string
    {
        return $this->preview_url;
    }

    public function setPreviewUrl(?string $preview_url): self
    {
        $this->preview_url = $preview_url;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
}
