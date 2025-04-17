<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/filter', name: 'product_filter_by_price')]
    public function filterByPrice(Request $request, ProductRepository $productRepository): Response
    {
        // Get the price range from the request
        $priceRange = $request->query->get('priceRange');
        
        // Create the query builder to filter products
        $queryBuilder = $productRepository->createQueryBuilder('p');

        // Apply the filters based on the selected price range
        switch ($priceRange) {
            case '10-29':
                $queryBuilder->where('p.price >= :minPrice')
                             ->andWhere('p.price <= :maxPrice')
                             ->setParameter('minPrice', 10)
                             ->setParameter('maxPrice', 29);
                break;
            case '30-35':
                $queryBuilder->where('p.price >= :minPrice')
                             ->andWhere('p.price <= :maxPrice')
                             ->setParameter('minPrice', 30)
                             ->setParameter('maxPrice', 35);
                break;
            case '35-50':
                $queryBuilder->where('p.price >= :minPrice')
                             ->andWhere('p.price <= :maxPrice')
                             ->setParameter('minPrice', 35)
                             ->setParameter('maxPrice', 50);
                break;
            default:
                // If no price range is selected, return all products
                break;
        }

        $products = $queryBuilder->getQuery()->getResult();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
