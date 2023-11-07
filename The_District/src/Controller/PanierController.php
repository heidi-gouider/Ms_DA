<?php

namespace App\Controller;

use App\Entity\Plat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/ajouter/{id}', name: 'ajouter')]
    public function add(Plat $plat, SessionInterface $session, Request $request)
    {

        $quantite = $request->request->get('quantite', 1); // Valeur par défaut 1 si non spécifiée
        $action = $request->request->get('action');

        if ($action === 'increment') {
            $quantite++;
        } elseif ($action === 'decrement' && $quantite > 1) {
            $quantite--;
        }        
        // Vérifiez et assurez-vous que $quantite est valide (un entier positif par exemple).

        // Obtenez le panier existant à partir de la session ou créez-en un nouveau.
        $panier = $session->get('panier', []);

        // Ajoutez ou mettez à jour la quantité du plat dans le panier.
        $panier[$plat->getId()] = $quantite;

        // Enregistrez le panier mis à jour dans la session.
        $session->set('panier', $panier);

        // Redirigez l'utilisateur vers une autre page ou effectuez toute autre action nécessaire.
        // Par exemple, redirigez l'utilisateur vers la page du panier.

        dd($session);
        // return $this->redirectToRoute('app_commande');

        // return $this->render('panier/index.html.twig', [
        //     'controller_name' => 'PanierController',
        // ]);
    }
}
