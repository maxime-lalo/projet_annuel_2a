<?php
$fRepo = new FoodRepository();
$json = json_decode(file_get_contents('php://input'), true);
if (isset($json['idFood']) &&
isset($json['idWarehouse']) &&
isset($json['quantity'])
){
    $update = $fRepo->setStock($json['idFood'],$json['idWarehouse'],$json['quantity']);

    if ($update) {
        new JsonReturn(JsonReturn::SUCCESS, "Food succesfully updated", 200);
    } else {
        new JsonReturn(JsonReturn::ERROR, "Error updating Food", 400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR, "Missing fields", 400);
}
