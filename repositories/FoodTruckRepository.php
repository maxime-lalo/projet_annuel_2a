<?php
require_once __DIR__ . "/../models/FoodTruck.php";
require_once __DIR__ . "/../models/FranchiseeOrder.php";
require_once __DIR__ . "/../models/ClientOrder.php";
require_once __DIR__ . "/MenuRepository.php";
require_once __DIR__ . "/AbstractRepository.php";


class FoodTruckRepository extends AbstractRepository
{
    public function getFromUserId(int $userId){
    	$truck = $this->dbManager->find("SELECT * FROM user a INNER JOIN food_truck b ON a.food_truck_id = b.id WHERE a.id = ?",[ $userId ]);
    	return $truck == null ? null: new FoodTruck($truck);
    }

    public function setBreakdown(FoodTruck $truck):bool{
        $rows = $this->dbManager->exec("INSERT INTO food_truck_breakdown (food_truck_id,user_id) VALUES (?,?)",[
            $truck->getId(),
            $this->getUser($truck)->getId()
        ]);
        return $rows == 1;
    }

    public function getUser(FoodTruck $truck):?User{
        $user = $this->dbManager->find("SELECT * FROM user WHERE food_truck_id = ?",[$truck->getId()]);
        return isset($user)? new User($user):null;
    }

    public function isOnBreakdown(FoodTruck $truck):bool{
        $breakdown = $this->dbManager->find('SELECT * FROM food_truck_breakdown WHERE food_truck_id = ? AND state < 2',[
            $truck->getId()
        ]);
        return $breakdown ? true:false;
    }

    public function cancelBreakdown(FoodTruck $truck):bool{
        $rows = $this->dbManager->exec("UPDATE food_truck_breakdown SET state = 2 WHERE food_truck_id = ?",[
            $truck->getId()
        ]);
        return $rows == 1;
    }

    public function update(FoodTruck $truck):bool{
        $rows = $this->dbManager->exec('UPDATE food_truck SET date_last_check = ?, mileage = ?, name = ?, city = ?, zipcode = ?, street_number = ?, street_name = ?, accepts_orders = ? WHERE id = ?',[
            $truck->getDateCheck(),
            $truck->getMileage(),
            $truck->getName(),
            $truck->getCity(),
            $truck->getZipcode(),
            $truck->getStreetNumber(),
            $truck->getStreetName(),
            $truck->getAcceptsOrders(),
            $truck->getId()
        ]);
        return $rows == 1;
    }

    public function add(FoodTruck $truck):?int{
        $rows = $this->dbManager->exec("INSERT INTO food_truck (date_register,mileage,brand,model) VALUES (?,?,?,?)",[
            $truck->getDateRegister(),
            $truck->getMileage(),
            $truck->getBrand(),
            $truck->getModel()
        ]);

        if ($rows == 1){
            return $this->dbManager->getLastInsertId();
        }else{
            return null;
        }
    }

    public function getBreakdownHistory(FoodTruck $truck):?array{
        $rows = $this->dbManager->getAll("SELECT * FROM food_truck_breakdown WHERE food_truck_id = ? ORDER BY date DESC",[
            $truck->getId()
        ]);

        if ($rows > 0){
            return $rows;
        }else{
            return null;
        }
    }

    public function getAllBreakdowns():?array
    {
        $rows = $this->dbManager->getAll('SELECT * FROM food_truck_breakdown WHERE state != 3 ORDER BY date DESC');

        return $rows > 0 ? $rows:null;
    }

    public function payBreakdownBill(int $idBreakdown):bool{
        return $this->changeBreakdownState($idBreakdown,3);
    }

    public function processBreakdown(int $idBreakdown)
    {
        return $this->changeBreakdownState($idBreakdown,1);
    }

    private function changeBreakdownState(int $idBreakdown,int $state):?bool{
        $rows = $this->dbManager->exec("UPDATE food_truck_breakdown SET state = ? WHERE id = ?",[
            $state,
            $idBreakdown
        ]);

        return $rows == 1;
    }

    public function sendBreakdownBill(int $price,string $description,int $idBreakdown)
    {
        $rows = $this->dbManager->exec("UPDATE food_truck_breakdown SET price = ?, description = ? WHERE id = ?",[
            intval($price),
            $description,
            $idBreakdown
        ]);

        return $rows == 1 ? $this->changeBreakdownState($idBreakdown,2):null;
    }

    public function getTrucksByDistance(array $foodTrucks, string $address):array{
        $trucks_ordered = array();
        foreach($foodTrucks as $foodTruck){
            $url_dest = urlencode($foodTruck->getFullAddress());
            $url_origin = urlencode($address);
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$url_origin."&destinations=".$url_dest."&mode=walking&key=".MAPS_API_KEY;
            $json = json_decode(file_get_contents($url));
            if(isset($json->status) && $json->status == "OK"){
                $foodTruck->setDistanceToClient($json->rows[0]->elements[0]->distance->value);
                if(count($trucks_ordered) >= 1 ){
                    $temp_array = array();
                    $truck_set = false;
                    for($i = 0; $i < count($trucks_ordered); $i++){
                        if($trucks_ordered[$i]->getDistanceToClient() < $foodTruck->getDistanceToClient()){
                            array_push($temp_array,$trucks_ordered[$i]);
                        }else{
                            $truck_set = true;
                            array_push($temp_array, $foodTruck);
                            array_push($temp_array, $trucks_ordered[$i]);
                        }
                    }
                    if(!$truck_set)array_push($temp_array, $foodTruck);
                    $trucks_ordered = $temp_array;
                }else{
                    array_push($trucks_ordered, $foodTruck);
                }
            }
        }
        $trucks_ordered = array_unique($trucks_ordered, SORT_REGULAR);
        return $trucks_ordered;
    }

    public function getTrucksAvailable(string $address){
        $foodTruckAv = array();
        $allTrucks = $this->getTrucksByDistance($this->getAll(), $address);
        $mRepo = new MenuRepository();
        foreach($allTrucks as $truck){
            $foodTrucksMenus = $mRepo->getAllAvailableFromTruck($truck->getId());
            if(!empty($foodTrucksMenus) && $truck->getAcceptsOrders() == 1)$foodTruckAv[] = $truck;
        }
        return $foodTruckAv;
    }

    public function addOrderToStock(FranchiseeOrder $order):bool{
        foreach($order->getFoods() as $food){
            $stock = $this->dbManager->find('SELECT * FROM stock WHERE id_food = ? AND id_food_truck = ? ',[
                $food->getId(),
                $order->getUser()->getTruck()->getId()
            ]);
            if($stock != null){
                $line = $this->dbManager->exec("UPDATE stock SET quantity = ? WHERE id_food = ? AND id_food_truck = ?",[
                    intval($food->getQuantity()/$food->getWeight()) + $stock['quantity'],
                    $food->getId(),
                    $order->getUser()->getTruck()->getId()
                ]);
            }else{
                $line = $this->dbManager->exec("INSERT INTO stock (id_food, id_food_truck, quantity) VALUES (?,?,?)",[
                    $food->getId(),
                    $order->getUser()->getTruck()->getId(),
                    $food->getQuantity()
                ]);
            }
            if($line < 1)return false;
        }
        return true;
    }

    public function getStock(int $truckId):array{
        $fRepo = new FoodRepository();
        $foods = array();
        $stocks = $this->dbManager->getAll("SELECT * FROM stock WHERE id_food_truck = ?",[
            $truckId
        ]);
        foreach($stocks as $food){
            $foodObj = $fRepo->getOneById($food['id_food']);
            $foodObj->setQuantity($food['quantity']*$foodObj->getWeight());
            $foods[] = $foodObj;
        }
        return $foods;
    }

    public function updateStock(int $truckId, array $stock):int{
        $lines = 0;
        foreach($stock as $food){
            $currentStock = $this->dbManager->find('SELECT * FROM stock WHERE id_food = ? AND id_food_truck = ? ',[
                $food->getId(),
                $truckId
            ]);
            if($currentStock != null){
                $line = $this->dbManager->exec("UPDATE stock SET quantity = ? WHERE id_food = ? AND id_food_truck = ?",[
                    intval($food->getQuantity()/$food->getWeight()),
                    $food->getId(),
                    $truckId
                ]);
                $lines += $line;
            }
        }
        return $lines;
    }

    public function updateStockFromOrder(ClientOrder $newOrder):array{
        $returnArray = array();
        $truckStock = $this->getStock($newOrder->getTruck()->getId());
        $checkOrderAv = true;
        foreach($newOrder->getMenus() as $menu){
            foreach($menu->getRecipes() as $recipe){
                foreach($recipe->getIngredients() as $ingredient){
                    foreach($truckStock as $ingredientAv){
                        if($ingredientAv->getId() == $ingredient->getId()){
                            $ingredientAvQuantity = $ingredientAv->getQuantity();
                            if($ingredientAvQuantity - ($ingredient->getQuantity()*$menu->getQuantity()) >= 0){
                                $ingredientAv->setQuantity($ingredientAvQuantity - intval($ingredient->getQuantity()*$menu->getQuantity()));
                            }else{
                                $menu->setIsMissing(1);
                                $checkOrderAv = false;
                            }
                        }
                    }
                }
            }
            foreach($menu->getIngredients() as $ingredient){
                foreach($truckStock as $ingredientAv){
                    if($ingredientAv->getId() == $ingredient->getId()){
                        $ingredientAvQuantity = $ingredientAv->getQuantity();
                        if($ingredientAvQuantity - ($ingredient->getQuantity()*$menu->getQuantity()) >= 0){
                            $ingredientAv->setQuantity($ingredientAvQuantity - intval($ingredient->getQuantity()*$menu->getQuantity()));
                        }else{
                            $menu->setIsMissing(1);
                            $checkOrderAv = false;
                        }
                    }
                }
            }
        }
        $returnArray += ["order"=>$newOrder];
        if($checkOrderAv){
            $this->updateStock($newOrder->getTruck()->getId(), $truckStock);
            $returnArray += ["status"=>1];
            return $returnArray;
        }else{
            $returnArray += ["status"=>0];
            return $returnArray;
        }
    }

    public function updateStockFromCanceledOrder(ClientOrder $order):int{
        $truckStock = $this->getStock($order->getTruck()->getId());
        foreach($order->getMenus() as $menu){
            foreach($menu->getRecipes() as $recipe){
                foreach($recipe->getIngredients() as $ingredient){
                    foreach($truckStock as $ingredientAv){
                        if($ingredientAv->getId() == $ingredient->getId()){
                            $ingredientAvQuantity = $ingredientAv->getQuantity();
                            $ingredientAv->setQuantity($ingredientAvQuantity + intval($ingredient->getQuantity()*$menu->getQuantity()));
                        }
                    }
                }
            }
            foreach($menu->getIngredients() as $ingredient){
                foreach($truckStock as $ingredientAv){
                    if($ingredientAv->getId() == $ingredient->getId()){
                        $ingredientAvQuantity = $ingredientAv->getQuantity();
                        $ingredientAv->setQuantity($ingredientAvQuantity + intval($ingredient->getQuantity()*$menu->getQuantity()));
                    }
                }
            }
        }
        return $this->updateStock($order->getTruck()->getId(), $truckStock) != 0;
    }
}