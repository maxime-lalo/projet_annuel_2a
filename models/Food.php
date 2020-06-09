<?php


class Food
{
    private int $id;
    private string $name;
    private float $weight;
    private string $type;

    private ?int $quantity;

    /**
     * Food constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->id = $parameters['id'];
        $this->name = $parameters['name'];
        $this->weight = $parameters['weight'];
        $this->type = $parameters['type'];

        $this->quantity = isset($parameters['quantity'])? $parameters['quantity']:null;
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
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|mixed|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int|mixed|null $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }


}