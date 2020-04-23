<?php
require_once __DIR__ . "/../models/Truck.php";
require_once __DIR__ . "/AbstractRepository.php";

class TruckRepository extends AbstractRepository
{
    public function getFromUserId(int $userId){
    	return $this->dbManager->find("SELECT * FROM user a INNER JOIN food_truck b ON a.food_truck_id = b.id WHERE a.id = ?",[ $userId ]);
    }
}