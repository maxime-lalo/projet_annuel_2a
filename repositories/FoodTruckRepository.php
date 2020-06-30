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
        $rows = $this->dbManager->exec('UPDATE food_truck SET date_last_check = ?, mileage = ?, name = ?, city = ?, zipcode = ?, street_number = ?, street_name = ? WHERE id = ?',[
            $truck->getDateCheck(),
            $truck->getMileage(),
            $truck->getName(),
            $truck->getCity(),
            $truck->getZipcode(),
            $truck->getStreetNumber(),
            $truck->getStreetName(),
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
}