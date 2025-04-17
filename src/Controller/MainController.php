<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupérer uniquement les produits mis en avant
        $products = $productRepository->findBy(['highlighted' => true]);

        return $this->render('main/index.html.twig', [
            'products' => $products,
        ]);
    }
}


