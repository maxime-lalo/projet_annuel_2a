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

    public function getOneByEmail(string $email):?User
    {
        $user = $this->dbManager->find("SELECT * FROM " . $this->getDbTable() . " WHERE email = ?" ,[
            $email
        ]);
        if ($user == null){
            return null;
        }else{
            return new User($user);
        }
    }

    public function getAllByEmailLike(string $email):?Array
    {
        $users = $this->dbManager->getAll("SELECT * FROM user WHERE email LIKE ?" ,[
            '%'.$email.'%'
        ]);
        $returnUsers = null;
        if ($users != null){
            $returnUsers = array();
            foreach ($users as $user){
                $returnUsers[] = new User($user);
            }
        }
        return $returnUsers;
    }

    public function update(User $user):bool{
        $rows = $this->dbManager->exec('UPDATE user SET firstname =?, lastname =? , email =?, password =? , phone =?, street_name =?,street_number =? , city =?, is_client =?, is_worker =?, is_employe =?, is_admin =?, food_truck_id =?, warehouse_id =?  WHERE id = ?', [
            $user->getFirstname(),
            $user->getLastname(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getPhone(),
            $user->getStreetName(),
            $user->getStreetNumber(),
            $user->getCity(),
            ($user->isClient())? 1: 0,
            ($user->isWorker())? 1: 0,
            ($user->isEmploye())? 1: 0,
            ($user->isAdmin())? 1: 0,
            ($user->getTruck() instanceof FoodTruck)?$user->getTruck()->getID() : null,
            ($user->getWarehouse() instanceof Warehouse)?$user->getWarehouse()->getId() : null,
            $user->getId()
        ]);
        return $rows == 1;
    }
}