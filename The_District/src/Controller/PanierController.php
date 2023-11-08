<?php

namespace App\Controller;

use App\Entity\Plat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/ajouter/{id}', name: 'ajouter')]
    public function add(Plat $plat, SessionInterface $session, Request $request)
    {

        $quantite = $request->request->get('quantite'); 
        $action = $request->request->get('action');

                // Effectue la logique de gestion de quantité
        // if ($quantite < 1) {
        //     $quantite = 1;
        // }        

        if ($action === 'increment') {
            $quantite++;
        } elseif ($action === 'decrement' && $quantite > 1) {
            $quantite--;
        }


        $form = $this->createFormBuilder()
        ->add('quantite', IntegerType::class, [
            'attr' => ['min' => 1, 'max' => 10], // Définis les valeurs minimales et maximales
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $quantite = $form->get('quantite')->getData();
        // Traitement des données, y compris la quantité choisie
    }
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
