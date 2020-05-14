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

    public function getNotActivatedWorkers():?Array{
        $users = $this->dbManager->getAll('SELECT * FROM user WHERE is_worker = 1 AND activated = 0');
        $returnUsers = null;
        if ($users != null){
            $returnUsers = array();
            foreach ($users as $user){
                $returnUsers[] = new User($user);
            }
        }
        return $returnUsers;
    }
}