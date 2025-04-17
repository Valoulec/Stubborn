<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Form\ProductFormType;
use Symfony\Component\HttpFoundation\Request;

class BackOfficeController extends AbstractController
{
    #[Route('/admin', name: 'app_back_office')]
    public function index(EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        }

        // Récupérer tous les produits depuis la base de données
        $products = $em->getRepository(Product::class)->findAll();

        $product = new Product();
        $productForm = $this->createForm(ProductFormType::class, $product);

        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
            'productForm' => $productForm->createView(),
            'products' => $products
        ]);
    }
    #[Route('/admin/add', name: 'admin_add_product')]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $product = new Product();
            $product->setName($request->request->get('name'));
            $product->setPrice(floatval($request->request->get('price')));
    
            // Gestion du fichier image
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($uploadsDirectory, $newFilename);
                $product->setImage($newFilename);
            }
    
            // Gestion du stock
            $stock = [
                'XS' => intval($request->request->get('stock_xs')),
                'S' => intval($request->request->get('stock_s')),
                'M' => intval($request->request->get('stock_m')),
                'L' => intval($request->request->get('stock_l')),
                'XL' => intval($request->request->get('stock_xl'))
            ];
            $product->setStock($stock);
    
            // Sauvegarde en BDD
            $entityManager->persist($product);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_back_office');
        }
    
        return $this->redirectToRoute('app_back_office');
    }
    
    
    #[Route('/admin/edit/{id}', name: 'admin_edit_product')]
    public function editProduct(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
    
        if ($request->isMethod('POST')) {
            // Mettre à jour le nom
            $product->setName($request->request->get('name'));
    
            // Mettre à jour le prix
            $product->setPrice((float) $request->request->get('price'));
    
            // Mettre à jour l'image si une nouvelle est envoyée
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($uploadsDirectory, $newFilename);
                $product->setImage($newFilename);
            }
    
            // Mettre à jour les stocks
            $stock = [
                'XS' => (int) $request->request->get('stock_XS'),
                'S' => (int) $request->request->get('stock_S'),
                'M' => (int) $request->request->get('stock_M'),
                'L' => (int) $request->request->get('stock_L'),
                'XL' => (int) $request->request->get('stock_XL'),
            ];
            $product->setStock($stock);
    
            // Sauvegarde des modifications
            $em->flush();
    
            // Redirection
            return $this->redirectToRoute('app_back_office');
        }
    
        return $this->render('back_office/edit.html.twig', [
            'product' => $product
        ]);
    }
    
    
#[Route('/admin/delete/{id}', name: 'admin_delete_product', methods: ['POST'])]
public function deleteProduct(int $id, Request $request, EntityManagerInterface $em): Response
{
    $product = $em->getRepository(Product::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException('Produit non trouvé.');
    }

    // Vérifier le token CSRF pour éviter les suppressions non autorisées
    if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
        $em->remove($product);
        $em->flush();
    }

    return $this->redirectToRoute('app_back_office'); // Retour direct à la page admin
}
}

