<?php


class Warehouse
{
    private int $id;
    private string $name;
    private string $street_name	;
    private int $street_number;
    private string $city;

    /**
     * Warehouse constructor.
     * @param int $id
     * @param string $name
     * @param string $street_name
     * @param int $street_number
     * @param string $city
     */
    public function __construct(array $warehouse)
    {
        $this->id = $warehouse['id'];
        $this->name = $warehouse['name'];
        $this->street_name = $warehouse['street_name'];
        $this->street_number = $warehouse['street_number'];
        $this->city = $warehouse['city'];
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
     * @return string
     */
    public function getStreetName(): string
    {
        return $this->street_name;
    }

    /**
     * @param string $street_name
     */
    public function setStreetName(string $street_name): void
    {
        $this->street_name = $street_name;
    }

    /**
     * @return int
     */
    public function getStreetNumber(): int
    {
        return $this->street_number;
    }

    /**
     * @param int $street_number
     */
    public function setStreetNumber(int $street_number): void
    {
        $this->street_number = $street_number;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }


}