<?php


class Recipe implements JsonSerializable
{
    private int $id;
    private string $name;
    private array $ingredients;

    /**
     * Recipe constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->id = $parameters['id'];
        $this->name = $parameters['name'];

        $this->ingredients = $parameters['ingredients'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * @param array $ingredients
     */
    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}