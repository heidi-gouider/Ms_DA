<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[isGranted('ROLE_CLIENT', message: "Vous devez avoir un compte pour accèder à cette page!")]
    #[Route('/commande', name: 'app_commande')]

    public function index(): Response
    {
        //on restrictionne l'accès ICI :
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
