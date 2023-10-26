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

        foreach ($commande as $comData) {
            $commandeDB = new Commande();
            $dateCommande = new \DateTime($comData['date_commande']);
            $commandeDB
                ->setId($comData['id'])
                // Je convertis la chaîne de caractères en un objet DateTime
                // ->setDateCommande($comData['date_commande'])
                // $dateCommande = new \DateTime($comData['date_commande'])
                ->setDateCommande($dateCommande)
                ->setTotal($comData['total'])
                ->setEtat((int)$comData['etat']);

            $manager->persist($commandeDB);

            // empêcher l'auto incrément
            $metadata = $manager->getClassMetaData(Commande::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }
        $manager->flush();

        $platRepo = $manager->getRepository(Plat::class);

        // J'initialise un tableau vide pour stoker les données de détail
        $detail = [];
        foreach ($detail as $detailData) {
            $detailDB = new detail();
            $detailDB
                ->setQuantite($detailData['quantite']);
            // j'obtient la commande correspondante à partir de l'Id spécifié dans $detailData['commande_id']
            $commande = $commandeRepo->find($detailData['commande_id']);
            $detailDB->setCommande($commande);

            $plat = $platRepo->find($detailData['plat_id']);
            $detailDB->setPlat($plat);



            $manager->persist($detailDB);
        }


        $manager->flush();
    }
}
