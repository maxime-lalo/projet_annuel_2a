<?php
$json = json_decode(file_get_contents('php://input'), true);

$tRepo = new FoodTruckRepository();

$truck = $tRepo->getOneById($json['id']);

$truck->setDateCheck($json['date']);
$truck->setMileage($json['mileage']);

$update = $tRepo->update($truck);

if ($update){
    $encodedTruck = json_encode($truck);
    $decode = json_decode($encodedTruck,true);
    $decode["status"] = "success";
    $decode["msg"] = "truck successfully updated";
    echo json_encode($decode);
}else{
    echo json_encode(["status" => "error","msg" => "error updating"]);
}