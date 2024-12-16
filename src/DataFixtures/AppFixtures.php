<?php

namespace App\DataFixtures;

use App\Entity\Article;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        /* Création d'un premier article*/
        $article = new Article();
        $article->setTitre("Premier article");
        $article->setTexte("Lorem ipsum");
        $manager->persist($article);

        /* Création d'un second article*/
        $article = new Article();
        $article->setTitre("Second article");
        $article->setTexte("Lorem ipsum");
        $manager->persist($article);

        $manager->flush();
    }
}
