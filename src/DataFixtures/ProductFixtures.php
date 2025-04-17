<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ["name" => "Blackbelt", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => true, "image" => "1.jpeg", "price" => 29.90],
            ["name" => "BlueBelt", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "2.jpeg", "price" => 29.90],
            ["name" => "Street", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "3.jpeg", "price" => 34.50],
            ["name" => "Pokeball", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => true, "image" => "4.jpeg", "price" => 45.00],
            ["name" => "PinkLady", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "5.jpeg", "price" => 29.90],
            ["name" => "Snow", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "6.jpeg", "price" => 32.00],
            ["name" => "Greyback", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "7.jpeg", "price" => 28.50],
            ["name" => "BlueCloud", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "8.jpeg", "price" => 45.00],
            ["name" => "BornInUsa", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => true, "image" => "9.jpeg", "price" => 59.90],
            ["name" => "GreenSchool", "stock" => ["XS" => 10, "S" => 10, "M" => 10, "L" => 10, "XL" => 10], "highlighted" => false, "image" => "10.jpeg", "price" => 42.20],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setStock($data['stock']);
            $product->setHighlighted($data['highlighted']); 
            $product->setImage($data['image']);
            $product->setPrice($data['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
