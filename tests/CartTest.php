<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;

class CartControllerTest extends WebTestCase
{
    public function testCartIndexIsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/cart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testAddToCart()
    {
        $client = static::createClient();
        $container = $client->getContainer();
    
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
    
        // Création d’un produit fictif avec un stock en taille M
        $product = new \App\Entity\Product();
        $product->setName('Produit Test')
            ->setPrice(19.99)
            ->setImage('test.jpg')
            ->setStock(['M' => 10]) // stock seulement pour taille M
            ->setHighlighted(false);
    
        $entityManager->persist($product);
        $entityManager->flush();
    
        $productId = $product->getId();
    
        // Préparation de la session
        $session = $container->get('session');
        $session->set('cart', []);
        $session->save();
    
        // Appel à l’URL d’ajout au panier
        $client->request('POST', '/cart/add/' . $productId, [
            'size' => 'M',
        ]);
    
        // Vérifie que la redirection vers /cart a bien lieu
        $this->assertResponseRedirects('/cart');
    }
    
    

    public function testRemoveFromCart()
    {
        $client = static::createClient();
        $session = $client->getContainer()->get('session');

        $session->set('cart', [
            99 => ['name' => 'Test', 'price' => 1000, 'size' => 'M', 'quantity' => 1]
        ]);
        $session->save();

        $client->request('GET', '/cart/remove/99');
        $this->assertResponseRedirects('/cart');

        $cart = $session->get('cart', []);
        $this->assertArrayNotHasKey(99, $cart);
    }

    public function testStripeCheckoutMock()
    {
        // Désactiver la vérification SSL pour Stripe (mode test uniquement)
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY'] ?? 'sk_test_xxx');
        Stripe::setVerifySslCerts(false); // ⚠️ uniquement pour tests en local

        $client = static::createClient();
        $session = $client->getContainer()->get('session');

        $session->set('cart', [
            1 => [
                'name' => 'Produit test',
                'price' => 1500,
                'quantity' => 1,
                'size' => 'L',
                'image' => 'image.jpg'
            ]
        ]);
        $session->save();

        $client->request('GET', '/payment-stripe');

        $this->assertResponseRedirects(); // Stripe redirige toujours vers une URL externe
    }

    public function testPaymentSuccess()
    {
        $client = static::createClient();
        $client->request('GET', '/payment-success');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Paiement réussi');
    }
}
