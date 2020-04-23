<?php


class Truck
{
    private int $id;
    private string $date_register;
    private string $date_check;
    private int $mileage;

    /**
     * Truck constructor.
     * @param int $id
     * @param string $date_register
     * @param string $date_check
     * @param int $mileage
     */
    public function __construct(array $truck)
    {
        $this->id = $truck['id'];
        $this->date_register = $truck['date_register'];
        $this->date_check = $truck['date_last_check'];
        $this->mileage = $truck['mileage'];
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
    public function getDateCheck(): string
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


}