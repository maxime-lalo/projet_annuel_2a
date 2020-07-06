<?php
require_once __DIR__ . "/../repositories/UserRepository.php";
require_once __DIR__ . "/../repositories/CaRepository.php";


class Ca implements JsonSerializable
{
    private int $id;
    private int $id_user;
    private DateTime $date;
    private float $price;
    private float $price_ca;

    /**
     * Ca constructor.
     * @param array $ca
     * @throws Exception
     */
    public function __construct(Array $ca)
    {
        $this->id = $ca['id'];
        $this->id_user = $ca['id_user'];
        $this->date = new DateTime($ca['date']);
        $this->price = $ca['price'];
        $this->price_ca = $ca['price_ca'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->id_user;
    }


    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceCa(): float
    {
        return $this->price_ca;
    }




    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}


