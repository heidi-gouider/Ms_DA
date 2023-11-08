<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, PlatRepository $platRepo)
    {
        $panier = $session->get('panier', []);

        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $plat = $platRepo->find($id);
            $data[] = [
                'plat' => $plat,
                'quantite' => $quantite
            ];
            $total += $plat->getPrix() * $quantite;
        }
        return $this->render('panier/index.html.twig', compact('data', 'total'));
        // dd($data);
    }

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
                'attr' => ['min' => 1, 'max' => 10],
                // Définis les valeurs minimales et maximales
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = $form->get('quantite')->getData();
            // Traitement des données, y compris la quantité choisie
        }
        // récuerer l'id du plat
        // $id = $plat->getId();
        // Obtenez le panier existant à partir de la session ou créez-en un nouveau.
        $panier = $session->get('panier', []);

        // Ajoutez ou mettez à jour la quantité du plat dans le panier.
        $panier[$plat->getId()] = $quantite;

        // Enregistrez le panier mis à jour dans la session.
        $session->set('panier', $panier);


         // Retournez une réponse, mais pas de redirection.
        //  return new Response('La quantité a été mise à jour dans le panier.');
        // dd($session);

        // return $this->redirectToRoute('panier_index');

 // Récupérez l'URL de la page actuelle et stockez-la dans la session.
        $referer = $request->headers->get('referer');
        $session->set('referer_url', $referer);

        // Redirigez l'utilisateur vers la page précédente.
        return $this->redirect($referer);        
        // return $this->render('panier/index.html.twig', [
        //     'controller_name' => 'PanierController',
        // ]);
    }

    #[Route('/ajout/{id}', name: 'ajout')]
    public function ajout(Plat $plat, SessionInterface $session)
    {
        $id = $plat->getId();
        $panier = $session->get('panier', []);

        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }
        
        $session->set('panier', $panier);


        return $this->redirectToRoute('panier_index');
    }

    #[Route('/retirer/{id}', name: 'retirer')]
    public function retirer(Plat $plat, SessionInterface $session)
    {

        // récuerer l'id du plat
        $id = $plat->getId();
        // Obtenez le panier existant à partir de la session ou créez-en un nouveau.
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1)
                $panier[$id]--;
        } else {
            unset($panier[$id]);
        }
        // Enregistrez le panier mis à jour dans la session.
        $session->set('panier', $panier);


        return $this->redirectToRoute('panier_index');
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(Plat $plat, SessionInterface $session)
    {
        $id = $plat->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);


        return $this->redirectToRoute('panier_index');
    }


}
