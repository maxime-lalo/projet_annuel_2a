<?php

$json = json_decode(file_get_contents('php://input'), true);

if (
    isset($json['id_user']) &&
    isset($json['id_food_truck']) &&
    isset($json['menus']) &&
    isset($json['use_points'])
) {
    $coRep = new ClientOrderRepository();
    $orderResult = $coRep->add($json['menus'], $json['id_user'], $json['id_food_truck'], $json['use_points']);
    if($orderResult["status"] === 1){
        new JsonReturn(JsonReturn::SUCCESS,"Order Created",201, $orderResult["order"]);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Could not create order missing ingredients",400);
    }
    
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}