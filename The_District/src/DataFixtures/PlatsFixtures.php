<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Plat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        include 'the_district_creation.php';
        $categorieRepo = $manager->getRepository(Categorie::class);

        foreach ($categorie as $catData){
            $categorieDB = new Categorie();
            $categorieDB
            ->setId($catData['categorie_id'])
            ->setLibelle($catData['libelle'])
            ->setImage($catData['image'])
            ->setActive($catData['active']);


            $manager->persist($categorieDB);

             // empêcher l'auto incrément
            $metadata = $manager->getClassMetaData(Categorie::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }
        $manager->flush();

        foreach ($plat as $platData) {
            $platDB = new plat();
            $platDB
            ->setLibelle($platData['libelle'])
            ->setDescription($platData['description'])
            ->setPrix($platData['prix'])
            ->setImage($platData['image'])
            ->setActive($platData['active']);
// j'obtient la catégorie correspondante à partir de l'Id spécifié dans $platData['categorie_id']
            $categorie = $categorieRepo->find($platData['categorie_id']);
            $platDB->setCategorie($categorie);

            $manager->persist($platDB);
        }
        $manager->flush();
    }
}
