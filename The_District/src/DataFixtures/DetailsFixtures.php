<?php

namespace App\DataFixtures;

use App\Entity\Detail;
use App\Entity\Commande;
use App\Entity\Plat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DetailsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        include 'the_district_creation.php';
        $commandeRepo = $manager->getRepository(Commande::class);

        foreach ($commande as $comData){
            $commandeDB = new Commande();
            $commandeDB
            ->setId($comData['id'])
            ->setDateCommande($comData['date_commande'])
            ->setTotal($comData['total'])
            ->setEtat($catData['etat']);


            $manager->persist($commandeDB);

             // empêcher l'auto incrément
            $metadata = $manager->getClassMetaData(Commande::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }
        $manager->flush();

        foreach ($detail as $detailData) {
            $detailDB = new detail();
            $detailDB
            ->setQuantite($detailData['quantite'])
// j'obtient la commande correspondante à partir de l'Id spécifié dans $detailData['commande_id']
            $commande = $commandeRepo->find($detailData['commande_id']);
            $platDB->setCategorie($categorie);

            $manager->persist($detailDB);
        }


        $manager->flush();
    }
}
