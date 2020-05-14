<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/AbstractRepository.php";

class UserRepository extends AbstractRepository
{
    public function getFromTruckId(int $userId){
    	$user = $this->dbManager->find("SELECT * FROM user WHERE user.food_truck_id = ?",[ $userId ]);
        if(isset($user)) return new User($user);
        else return null;
    }

    public function setTruckFromUser(int $userId , int $truckId){
        $user = $this->dbManager->exec("UPDATE user SET food_truck_id = ?  WHERE id = ?",[ $truckId , $userId ]);
        return null;

    }
}