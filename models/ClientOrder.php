<?php
require_once __DIR__ . "/../repositories/UserRepository.php";
require_once __DIR__ . "/../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../repositories/FoodRepository.php";
require_once __DIR__ . "/../repositories/RecipeRepository.php";
require_once __DIR__ . "/../repositories/MenuRepository.php";

class ClientOrder implements JsonSerializable
{
    private int $id;
    private User $user;
    private FoodTruck $truck;
    private DateTime $date;
    private array $menus;
    private int $status;
    private float $totalPrice;

    /**
     * ClientOrder constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];

        $uRepo = new UserRepository();
        $this->user = $uRepo->getOneById($data['id_user']);

        $ftRepo = new FoodTruckRepository();
        $this->truck = $ftRepo->getOneById($data['id_food_truck']);

        $this->date = new DateTime($data['date']);

        $this->totalPrice = 0.0;
        if(isset($data['menus'])){
            $rRepo = new RecipeRepository();
            $fRepo = new FoodRepository();
            $mRepo = new MenuRepository();
            for($i = 0; $i < count($data['menus']); $i++){
                $recipes = array();
                $ingredients = array();
                $this->menus[] = $mRepo->getOneById($data['menus'][$i]['id']);
                foreach($data['menus'][$i]['recipes'] as $recipe){
                    $recipes[] = $rRepo->getOneById($recipe['id']);
                }
                $this->menus[$i]->setRecipes($recipes);
                
                foreach($data['menus'][$i]['ingredients'] as $ingredient){
                    $ingredients[] = $fRepo->getOneById($ingredient['id']);
                }
                $this->menus[$i]->setIngredients($ingredients);

                $this->menus[$i]->setQuantity($data['menus'][$i]['quantity']);
                $this->menus[$i]->setUuid($data['menus'][$i]['uuid']);
                $this->totalPrice += $this->menus[$i]->getPrice();
            }
        }
        
        $this->status = (isset($data['status']))?$data['status']:0;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return int|mixed
     */
    public function getId(): int
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
    public function getUser(): User
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
     * @return mixed|FoodTruck|null
     */
    public function getTruck(): FoodTruck
    {
        return $this->truck;
    }

    /**
     * @param mixed|FoodTruck|null $truck
     */
    public function setTruck(Foodtruck $truck): void
    {
        $this->truck = $truck;
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
    public function getMenus(): array
    {
        return $this->menus;
    }

    /**
     * @param array|mixed $menus
     */
    public function setMenus($menus): void
    {
        $this->menus = $menus;
    }

    /**
     * @return int|mixed
     */
    public function getStatus():int
    {
        return $this->status;
    }

    /**
     * @param int|mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return float|mixed
     */ 
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float|mixed $totalPrice
     */ 
    public function setTotalPrice(float $totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }
}