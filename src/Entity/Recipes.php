<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $cook_time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cuisine = null;

    /**
     * @var Collection<int, Ingredients>
     */
    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    private Collection $ingredient_id;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    public function __construct()
    {
        $this->ingredient_id = new ArrayCollection();
    }

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCookTime(): ?float
    {
        return $this->cook_time;
    }

    public function setCookTime(float $cook_time): static
    {
        $this->cook_time = $cook_time;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(?string $cuisine): static
    {
        $this->cuisine = $cuisine;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredientId(): Collection
    {
        return $this->ingredient_id;
    }

    public function addIngredientId(Ingredients $ingredientId): static
    {
        if (!$this->ingredient_id->contains($ingredientId)) {
            $this->ingredient_id->add($ingredientId);
        }

        return $this;
    }

    public function removeIngredientId(Ingredients $ingredientId): static
    {
        $this->ingredient_id->removeElement($ingredientId);

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    
}
