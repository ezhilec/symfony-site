<?php
namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;

class ImageService
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Filesystem $filesystem,
        private readonly ImageManager $imageManager
    ) {}
    
    public function addImages(Product $product, array $images, string $photoDir): void
    {
        foreach ($images as $i => $imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move($photoDir, $newFilename);
    
            $thumbnail = $this->createThumbnail($newFilename, 200, 200, $photoDir);
            
            $image = new ProductImage();
            $image->setUrl($newFilename);
            $image->setPriority($i);
            $image->setPreviewUrl($thumbnail);
            $product->addImage($image);
        }
    }
    
    public function deleteImages(array $imageIds, string $photoDir): void
    {
        $imagesToDelete = $this->imageRepository->findBy(['id' => $imageIds]);
        foreach ($imagesToDelete as $image) {
            $this->entityManager->remove($image);
            $this->filesystem->remove($photoDir . '/' . $image->getUrl());
        }
        $this->entityManager->flush();
    }
    
    public function createThumbnail(string $filename, int $width, int $height, string $photoDir): string
    {
        $image = $this->imageManager->make($photoDir . '/' . $filename);
        $image->fit($width, $height);
        
        $thumbnailFilename = 'thumb_' . $filename;
        $thumbnailPath = $photoDir . '/' . $thumbnailFilename;
        $image->save($thumbnailPath);
        
        return $thumbnailFilename;
    }
}
