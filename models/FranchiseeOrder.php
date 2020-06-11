<?php
require_once __DIR__ . "/../repositories/UserRepository.php";
require_once __DIR__ . "/../repositories/WarehouseRepository.php";

class FranchiseeOrder
{
    private int $id;

    private User $user;
    private Warehouse $warehouse;
    private DateTime $date;

    private array $foods;
    private array $missing;

    private array $recipes;
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
        $warehouse = $wRepo->getOneById($data['warehouse']);
        $this->warehouse = $warehouse;

        $wRepo = new UserRepository();
        $user = $wRepo->getOneById($data['user']);
        $this->user = $user;

        $this->date = new DateTime($data['date']);

        $this->foods = $data['foods'];
        $this->missing = $data['$missing'];
        $this->recipes = $data['recipes'];
        $this->percentage = $data['percentage'];
    }


}