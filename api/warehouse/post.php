<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($json['name']) &&
    isset($json['street_name']) &&
    isset($json['street_number']) &&
    isset($json['city']) &&
    isset($json['zipcode'])
) {
    $warehouse = new Warehouse([
        "id" => 0,
        "name" => $json['name'],
        "street_name" => $json['street_name'],
        "street_number" => $json['street_number'],
        "city" => $json['city'],
        "zipcode" => $json['zipcode']
    ]);
    $wRepo = new WarehouseRepository();

    $res = $wRepo->add($warehouse);

    if ($res != null){
        $warehouse->setId($res);
    }

    if ($res){
        new JsonReturn(JsonReturn::SUCCESS,"Warehouse created",201,$warehouse);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Error creating Warehouse",400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
}