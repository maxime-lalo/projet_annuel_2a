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
    private ?int $quantity;
    private ?string $uuid;
    private ?int $is_missing;

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
        $this->quantity = (isset($parameters['quantity']))?$parameters['quantity'] : 1;
        $this->is_missing = (isset($parameters['is_missing']))?$parameters['is_missing'] : 0;
        $this->uuid = (isset($parameters['uuid']))?$parameters['uuid'] : 'none';
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

    /**
     * Get the value of quantity
     */ 
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     */ 
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get the value of uuid
     */ 
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid
     *
     */ 
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get the value of is_missing
     */ 
    public function isMissing():int
    {
        return $this->is_missing;
    }

    /**
     * Set the value of is_missing
     *
     */ 
    public function setIsMissing(int $is_missing)
    {
        $this->is_missing = $is_missing;
    }
}