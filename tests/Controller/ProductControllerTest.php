<?php

namespace Controller;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProductRepository $repository;
    private string $path = '/admin/product/';
    
    private EntityManager $entityManager;
    private User $adminUser;
    private ProductCategory $category;
    
    private ?Registry $doctrine;
    
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->doctrine = static::getContainer()->get('doctrine');
        
        $this->repository = $this->doctrine->getRepository(Product::class);

        $this->entityManager = $this->doctrine->getManager();
        
        $adminUser = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin1@test.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin1@test.com');
            $adminUser->setName('Admin');
            $adminUser->setRoles([User::ROLE_ADMIN]);
            $adminUser->setPassword('123');
            $adminUser->setIsActive(true);
            $this->entityManager->persist($adminUser);
            $this->entityManager->flush();
        }
        $this->adminUser = $adminUser;
    
        $category = $this->doctrine->getRepository(ProductCategory::class)->findOneBy(['slug' => 'testing']);
        if (!$category) {
            $category = new ProductCategory();
            $category->setName('Testing');
            $category->setSlug('testing');
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }
        $this->category = $category;
        
        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }
    
    public function testIndex(): void
    {
        $this->client->loginUser($this->adminUser);
        $crawler = $this->client->request('GET', $this->path);
        
        self::assertResponseStatusCodeSame(200);
        
        $this->assertSame('Product index', $crawler->filter('h1')->first()->text());
    }
    
    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        
        $this->client->loginUser($this->adminUser);
        $this->client->request('GET', sprintf('%snew', $this->path));
        
        self::assertResponseStatusCodeSame(200);
        
        $this->client->submitForm('Save', [
            'product[name]' => 'Testing',
            'product[reference_number]' => 'Testing',
            'product[category]' => $this->category->getId(),
        ]);
        
        self::assertResponseRedirects('/admin/product/');
        
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }
    
    public function testShow(): void
    {
        $fixture = new Product();
        $fixture->setName('My Title');
        $fixture->setReferenceNumber('My Title');
        $fixture->setCategory($this->category);
        
        $this->repository->save($fixture, true);
    
        $this->client->loginUser($this->adminUser);
        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        
        self::assertResponseStatusCodeSame(200);
        $this->assertSame('My Title', $crawler->filter('h1')->first()->text());
    }
    
    public function testEdit(): void
    {
        $fixture = new Product();
        $fixture->setName('My Title');
        $fixture->setReferenceNumber('My Title');
        $fixture->setCategory($this->category);
        
        $this->repository->save($fixture, true);
    
        $this->client->loginUser($this->adminUser);
        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
        
        $this->client->submitForm('Update', [
            'product[name]' => 'Something New',
            'product[reference_number]' => 'Something New',
            'product[category]' => $this->category->getId(),
        ]);
        
        self::assertResponseRedirects('/admin/product/');
        
        $fixture = $this->repository->findAll();
        
        self::assertSame('Something New', $fixture[0]->getName());
    }
    
    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        
        $fixture = new Product();
        $fixture->setName('My Title');
        $fixture->setReferenceNumber('My Title');
        $fixture->setCategory($this->category);
        
        $this->repository->save($fixture, true);
        
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    
        $this->client->loginUser($this->adminUser);
        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');
        
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/admin/product/');
    }
}
