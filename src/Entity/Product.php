<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column]
    private array $stock = [
        'XS' => 0,
        'S' => 0,
        'M' => 0,
        'L' => 0,
        'XL' => 0
    ];

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'boolean')]
    private bool $highlighted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): array
    {
        return $this->stock ?? [
            'XS' => 0,
            'S' => 0,
            'M' => 0,
            'L' => 0,
            'XL' => 0
        ];
    }

    public function setStock(array $stock): static
    {
        $defaultStock = [
            'XS' => 0,
            'S' => 0,
            'M' => 0,
            'L' => 0,
            'XL' => 0
        ];

        $this->stock = array_merge($defaultStock, $stock);

        return $this;
    }

    public function getStockForSize(string $size): int
    {
        return $this->stock[$size] ?? 0;
    }

    public function setStockForSize(string $size, int $value): self
    {
        if (isset($this->stock[$size])) {
            $this->stock[$size] = $value;
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function isHighlighted(): bool
    {
        return $this->highlighted;
    }

    public function setHighlighted(bool $highlighted): self
    {
        $this->highlighted = $highlighted;
        return $this;
    }
}
