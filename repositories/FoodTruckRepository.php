<?php
require_once __DIR__ . "/../models/FoodTruck.php";
require_once __DIR__ . "/AbstractRepository.php";

class FoodTruckRepository extends AbstractRepository
{
    public function getFromUserId(int $userId){
    	$truck = $this->dbManager->find("SELECT * FROM user a INNER JOIN FOOD_TRUCK b ON a.food_truck_id = b.id WHERE a.id = ?",[ $userId ]);
    	return new FoodTruck($truck);
    }
}