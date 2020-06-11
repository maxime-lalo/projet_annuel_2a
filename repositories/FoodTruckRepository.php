<?php
require_once __DIR__ . "/../models/FoodTruck.php";
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
        $breakdown = $this->dbManager->find('SELECT * FROM food_truck_breakdown WHERE food_truck_id = ? AND state = 0',[
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
        $rows = $this->dbManager->exec('UPDATE food_truck SET date_last_check = ?, mileage = ? WHERE id = ?',[
            $truck->getDateCheck(),
            $truck->getMileage(),
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
}