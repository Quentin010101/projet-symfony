<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for($c = 0; $c < 3; $c++){
            $category = new Category;
            $category->setName($faker->title())
            ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for($p=0; $p<mt_rand(15, 20); $p++){
                $product = new Product;
                $product->setName($faker->sentence())
                ->setPrice(mt_rand(100, 200))
                ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400, true));
    
                $manager->persist($product);
            }
        }


        $manager->flush();
    }
}
