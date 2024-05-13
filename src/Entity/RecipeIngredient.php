<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
class RecipeIngredient
{
  
   

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\Id]
    private ?Recipes $recipe_id = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\Id]
    private ?Ingredients $ingredient_id = null;

  

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

    public function getRecipeId(): ?Recipes
    {
        return $this->recipe_id;
    }

    public function setRecipeId(?Recipes $recipe_id): static
    {
        $this->recipe_id = $recipe_id;

        return $this;
    }

    public function getIngredientId(): ?Ingredients
    {
        return $this->ingredient_id;
    }

    public function setIngredientId(?Ingredients $ingredient_id): static
    {
        $this->ingredient_id = $ingredient_id;

        return $this;
    }
}
