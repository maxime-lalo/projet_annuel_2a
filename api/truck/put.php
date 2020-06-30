<?php
$json = json_decode(file_get_contents('php://input'), true);

$tRepo = new FoodTruckRepository();

$truck = $tRepo->getOneById($json['id']);

$truck->setDateCheck($json['date']);
$truck->setMileage($json['mileage']);
$truck->setName($json['name']);
$truck->setCity($json['city']);
$truck->setZipcode($json['zipcode']);
$truck->setStreetNumber($json['street_number']);
$truck->setStreetName($json['street_name']);

$update = $tRepo->update($truck);

if ($update){
    new JsonReturn(JsonReturn::SUCCESS,"Truck succesfully updated",200,$truck);
}else{
    new JsonReturn(JsonReturn::ERROR,"Error updating Truck",400);
}