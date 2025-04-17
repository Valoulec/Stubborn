<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_detail', requirements: ['id' => '\d+'])]
    public function index(int $id, ProductRepository $productRepository): Response    
    {
        dump($id); // Affiche l'ID dans la barre de debug
        dump($productRepository->find($id)); // Vérifie si le produit est trouvé
    
        $product = $productRepository->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
    
        return $this->render('detail/index.html.twig', [
            'product' => $product,
        ]);
    }
    
}
  



