<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    private $categorieRepo;
    private $platRepo;

    public function __construct(CategorieRepository $categorieRepo, PlatRepository $platRepo)
    {
        $this->categorieRepo = $categorieRepo;
        $this->platRepo = $platRepo;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    #[Route('/plats', name: 'app_plats')]
    public function plats(): Response
    {
        //on appelle la fonction `findAll()` du repository de la classe `Plat` afin de récupérer tous les plats de la base de données;
        $plats = $this->platRepo->findAll();
        dump($plats);

        return $this->render('catalogue/plats.html.twig', [
            'controller_name' => 'CatalogueController',

            'plats' => $plats
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function categories(): Response
    {
        $categories = $this->categorieRepo->findAll();
        dump($categories);
        return $this->render('catalogue/categories.html.twig', [
            // 'controller_name' => 'CatalogueController',
            'categories' => $categories
        ]);
    }
}
