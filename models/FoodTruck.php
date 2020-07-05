<?php


class FoodTruck implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $date_register;
    private ?string $date_check;
    private int $mileage;
    private string $brand;
    private string $model;
    private string $city;
    private string $zipcode;
    private string $street_name;
    private int $street_number;
    private ?int $distance_to_client;
    private int $accepts_orders;

    /**
     * FoodTruck constructor.
     * @param array $truck
     */
    public function __construct(array $truck)
    {
        $this->id = $truck['id'];
        $this->name = $truck['name'];
        $this->date_register = $truck['date_register'];
        $this->date_check = $truck['date_last_check'];
        $this->mileage = $truck['mileage'];
        $this->brand = $truck['brand'];
        $this->model = $truck['model'];
        $this->city = $truck['city'];
        $this->zipcode = $truck['zipcode'];
        $this->street_name = $truck['street_name'];
        $this->street_number = $truck['street_number'];
        $this->accepts_orders = $truck['accepts_orders'];
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
    public function getDateRegister(): string
    {
        return $this->date_register;
    }

    /**
     * @param string $date_register
     */
    public function setDateRegister(string $date_register): void
    {
        $this->date_register = $date_register;
    }

    /**
     * @return string
     */
    public function getDateCheck(): ?string
    {
        return $this->date_check;
    }

    /**
     * @param string $date_check
     */
    public function setDateCheck(string $date_check): void
    {
        $this->date_check = $date_check;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    /**
     * @param int $mileage
     */
    public function setMileage(int $mileage): void
    {
        $this->mileage = $mileage;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
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

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     */
    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
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
     * @return int
     */
    public function getDistanceToClient(): int
    {
        return $this->distance_to_client;
    }

    /**
     * @param int $distance_to_client
     */
    public function setDistanceToClient(int $distance_to_client): void
    {
        $this->distance_to_client = $distance_to_client;
    }

    public function getFullAddress():string
    {
        return $this->getStreetNumber().' '.$this->getStreetName().', '.$this->getZipcode().' '.$this->getCity();
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    /**
     * Get the value of accepts_orders
     */ 
    public function getAcceptsOrders():int
    {
        return $this->accepts_orders;
    }

    /**
     * Set the value of accepts_orders
     *
     */ 
    public function setAcceptsOrders(int $accepts_orders)
    {
        $this->accepts_orders = $accepts_orders;
    }
}