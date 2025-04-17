<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(SessionInterface $session, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);
        
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $size = $request->request->get('size', 'M');
        
        if (!isset($cart[$id])) {
            $cart[$id] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'size' => $size,
                'quantity' => 1
            ];
        } else {
            $cart[$id]['quantity']++;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(SessionInterface $session, int $id): Response
    {
        $cart = $session->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/payment-stripe', name: 'payment_stripe')]
    public function paymentStripe(SessionInterface $session)
    {
        // Récupérer la clé API Stripe depuis les variables d'environnement
        $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY']; // Utilisation de la clé définie dans .env
        Stripe::setApiKey($stripeSecretKey);  // Utilise la clé API

        $cart = $session->get('cart', []);
        $totalAmount = 0;
        $lineItems = [];

        // Préparer les items pour la session de paiement Stripe
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100,  // Convertir en centimes
                ],
                'quantity' => $item['quantity'],
            ];

            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Créer une session Stripe
        $sessionStripe = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], 0),
            'cancel_url' => $this->generateUrl('cart', [], 0),
        ]);

        // Rediriger vers la page de paiement Stripe
        return $this->redirect($sessionStripe->url);
    }

    #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        return $this->render('cart/payment_success.html.twig', [
            'message' => 'Paiement réussi !',
        ]);
    }
}
