<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private $userRepo;

    public function __construct(UtilisateurRepository $userRepo){
        $this->userRepo = $userRepo;
    }

    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        // Récuperation de l'identifiant unique de l'utilisateur connecté
        $identifiant = $this->getUser()->getUserIdentifier();
        // Vérification de la présence de l'utilisateur dans la bdd en lien avec le mail fournit par l'utilisateur
        if($identifiant){
            $info = $this->userRepo->findOneBy(["email" =>$identifiant]);
        }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'informations' => $info
        ]);
    }
}
