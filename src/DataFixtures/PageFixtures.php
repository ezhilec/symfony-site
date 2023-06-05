<?php

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setName('Home');
        $page->setSlug('');
        $page->setPriority(1);
        $page->setIsActive(true);
        $page->setIsVisible(true);
        $page->setCaption('Home page');
        $page->setContent('Home page content');
        $manager->persist($page);
    
        $page = new Page();
        $page->setName('About us');
        $page->setSlug('about');
        $page->setPriority(1);
        $page->setIsActive(true);
        $page->setIsVisible(true);
        $page->setCaption('About us');
        $page->setContent('About us content');
        $manager->persist($page);
    
        $page = new Page();
        $page->setName('Contact');
        $page->setSlug('contact');
        $page->setPriority(1);
        $page->setIsActive(true);
        $page->setIsVisible(true);
        $page->setCaption('Contact');
        $page->setContent('Contact content');
        $manager->persist($page);
        
        $manager->flush();
    }
}
