<?php
$json = json_decode(file_get_contents('php://input'), true);

$tRepo = new FoodTruckRepository();

if(
    isset($json['id']) && 
    isset($json['date']) &&
    isset($json['mileage']) &&
    isset($json['name']) &&
    isset($json['city']) &&
    isset($json['zipcode']) &&
    isset($json['street_number']) &&
    isset($json['street_name']) 
){
    $truck = $tRepo->getOneById($json['id']);
    if($truck != null){
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
    }else{
        new JsonReturn(JsonReturn::ERROR,"Truck not found",404);
    }
    
}elseif(isset($json['id']) && isset($json['city']) && isset($json['zipcode']) && isset($json['street_number']) && isset($json['street_name'])){
    $truck = $tRepo->getOneById($json['id']);
    if($truck != null){
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
    }else{
        new JsonReturn(JsonReturn::ERROR,"Truck not found",404);
    }
}elseif(isset($json['id']) && isset($json['accepts_orders'])){
    $truck = $tRepo->getOneById($json['id']);
    if($truck != null){
        $truck->setAcceptsOrders($json['accepts_orders']);
        $update = $tRepo->update($truck);
        if ($update){
            new JsonReturn(JsonReturn::SUCCESS,"Truck succesfully updated",200,$truck);
        }else{
            new JsonReturn(JsonReturn::ERROR,"Error updating Truck",400);
        } 
    }else{
        new JsonReturn(JsonReturn::ERROR,"Truck not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing Arguments",400);
}