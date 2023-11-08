<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande', name: 'app_commande_')]
class CommandeController extends AbstractController
{
    #[isGranted('ROLE_CLIENT', message: "Vous devez avoir un compte pour accèder à cette page!")]

    #[Route('/ajout', name: 'ajout')]
    public function ajout(SessionInterface $session, PlatRepository $platRepository, EntityManagerInterface $em): Response
    {
        //on restrictionne l'accès ICI :
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $panier = $session->get('panier', []);


        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('accueil');
        }
  //Le panier n'est pas vide, on crée la commande
  $commande = new Commande();

  // On remplit la commande
  $commande->setUtilisateur($this->getUser());

  // On parcourt le panier pour créer les détails de commande
  foreach($panier as $item => $quantite){
      $Detail = new Detail();

      // On va chercher le produit
      $plat = $platRepository->find($item);
      
      $prix = $plat->getPrix();

      // On crée le détail de commande
      $Detail->setPlat($plat);
    //   $Detail->setTotal($prix);
      $Detail->setQuantite($quantite);

      $commande->addDetail($Detail);
  }

  // On persiste et on flush
  $em->persist($commande);
  $em->flush();

  $session->remove('panier');

  $this->addFlash('message', 'Commande créée avec succès');
  return $this->redirectToRoute('accueil');
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
