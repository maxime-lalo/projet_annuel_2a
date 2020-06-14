<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($json['brand']) &&
    isset($json['model']) &&
    isset($json['mileage'])
) {
    if (!isset($json['date_last_check'])){
        $json['date_last_check'] = null;
    }
    $truck = new FoodTruck([
        "id" => 0,
        "date_register" => date('Y-m-d'),
        "date_last_check" => $json['date_last_check'],
        "mileage" => $json['mileage'],
        "brand" => $json['brand'],
        "model" => $json['model']
    ]);
    $tRepo = new FoodTruckRepository();

    $res = $tRepo->add($truck);

    if ($res != null){
        $truck->setId($res);
    }

    if ($res){
        new JsonReturn(JsonReturn::SUCCESS,"Truck created",201,$truck);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Error creating truck",400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
}