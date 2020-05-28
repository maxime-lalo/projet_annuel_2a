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
        $users = $this->dbManager->getAll('SELECT * FROM user WHERE is_worker = 1 AND activated = 0 ORDER BY id DESC');
        $returnUsers = null;
        if ($users != null){
            $returnUsers = array();
            foreach ($users as $user){
                $returnUsers[] = new User($user);
            }
        }
        return $returnUsers;
    }

    public function getAllWorkers():?Array{
        $users = $this->dbManager->getAll('SELECT * FROM user WHERE is_worker = 1 AND activated = 1 ORDER BY id DESC');
        $returnUsers = null;
        if ($users != null){
            $returnUsers = array();
            foreach ($users as $user){
                $returnUsers[] = new User($user);
            }
        }
        return $returnUsers;
    }

    public function processWorker(int $id, string $type):?bool{
        if ($type == "accept"){
            // TODO
            // Envoyer un mail pour prévenir que l'utilisateur est accepté
            $u = $this->dbManager->exec('UPDATE user SET activated = 1 WHERE id = ?',[$id]);
        }else{
            // TODO
            // Envoyer un mail pour prévenir que l'utilisateur est refusé
            $u = $this->dbManager->exec('UPDATE user SET activated = 2 WHERE id = ?',[$id]);
        }
        return $u > 0;
    }

    public function setTruckFromUser(int $userId , int $truckId){
        $user = $this->dbManager->exec("UPDATE user SET food_truck_id = ?  WHERE id = ?",[ $truckId , $userId ]);
        return null;

    }
}