<?php
require_once __DIR__ . "/../repositories/UserRepository.php";
require_once __DIR__ . "/../repositories/WarehouseRepository.php";
require_once __DIR__ . "/../repositories/FoodRepository.php";

class FranchiseeOrder
{
    private int $id;

    private User $user;
    private Warehouse $warehouse;
    private DateTime $date;

    private array $foods;
    private array $missing;

    private float $percentage;

    /**
     * FranchiseeOrder constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];

        $wRepo = new WarehouseRepository();
        $warehouse = $wRepo->getOneById($data['id_warehouse']);
        $this->warehouse = $warehouse;

        $wRepo = new UserRepository();
        $user = $wRepo->getOneById($data['id_user']);
        $this->user = $user;

        $this->date = new DateTime($data['date']);

        $this->foods = $data['foods'];

        $fRepo = new FoodRepository();
        foreach(json_decode($data['missing'],true) as $missingIngredient){
            $getIng = $fRepo->getOneById($missingIngredient['id']);
            $getIng->setQuantity($missingIngredient['quantity']);
            $this->missing[] = $getIng;
        }
        $this->percentage = $data['percentage'];
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed|User|null $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed|Warehouse|null
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * @param mixed|Warehouse|null $warehouse
     */
    public function setWarehouse($warehouse): void
    {
        $this->warehouse = $warehouse;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array|mixed
     */
    public function getFoods()
    {
        return $this->foods;
    }

    /**
     * @param array|mixed $foods
     */
    public function setFoods($foods): void
    {
        $this->foods = $foods;
    }

    /**
     * @return array|mixed
     */
    public function getMissing()
    {
        return $this->missing;
    }

    /**
     * @param array|mixed $missing
     */
    public function setMissing($missing): void
    {
        $this->missing = $missing;
    }

    /**
     * @return float|mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param float|mixed $percentage
     */
    public function setPercentage($percentage): void
    {
        $this->percentage = $percentage;
    }



}