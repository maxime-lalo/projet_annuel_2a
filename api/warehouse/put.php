<?php
$json = json_decode(file_get_contents('php://input'), true);

$wRepo = new WarehouseRepository();

$warehouse = $wRepo->getOneById($json['id']);

$warehouse->setName($json['name']);

$update = $wRepo->update($warehouse);

if ($update){
    $encodedWarehouse = json_encode($warehouse);
    $decode = json_decode($encodedWarehouse,true);
    $decode["status"] = "success";
    $decode["msg"] = "Warehouse successfully updated";
    echo json_encode($decode);
}else{
    echo json_encode(["status" => "error","msg" => "error updating"]);
}