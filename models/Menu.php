<?php

class Menu implements JsonSerializable
{
    private int $id;
    private string $name;
    private ?array $recipes;
    private ?array $ingredients;
    private float $price;
    private int $recipes_num;
    private int $ingredients_num;

    /**
     * Recipe constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->id = $parameters['id'];
        $this->name = $parameters['name'];
        $this->ingredients = $parameters['ingredients'];
        $this->recipes = $parameters['recipes'];
        $this->price = $parameters['price'];
        $this->recipes_num = $parameters['recipes_num'];
        $this->ingredients_num = $parameters['ingredients_num'];
    }

    /**
     * Get the value of id
     */ 
    public function getId():int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */ 
    public function getName():string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     */ 
    public function setName(string $name):void
    {
        $this->name = $name;
    }

    /**
     * Get the value of recipes
     */ 
    public function getRecipes():array
    {
        return $this->recipes;
    }

    /**
     * Set the value of recipes
     *
     */ 
    public function setRecipes(array $recipes):void
    {
        $this->recipes = $recipes;
    }

    /**
     * Get the value of ingredients
     */ 
    public function getIngredients():array
    {
        return $this->ingredients;
    }

    /**
     * Set the value of ingredients
     *
     */ 
    public function setIngredients(array $ingredients):void
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice():float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     */ 
    public function setPrice(float $price):void
    {
        $this->price = $price;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * Get the value of recipes_num
     */ 
    public function getRecipesNum():int
    {
        return $this->recipes_num;
    }

    /**
     * Set the value of recipes_num
     *
     */ 
    public function setRecipesNum(int $recipes_num):void
    {
        $this->recipes_num = $recipes_num;
    }

    /**
     * Get the value of ingredients_num
     */ 
    public function getIngredientsNum():int
    {
        return $this->ingredients_num;
    }

    /**
     * Set the value of ingredients_num
     *
     */ 
    public function setIngredientsNum(int $ingredients_num):void
    {
        $this->ingredients_num = $ingredients_num;
    }
}