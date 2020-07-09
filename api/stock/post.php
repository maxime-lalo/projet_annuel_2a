<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($_POST['name']) &&
    isset($_POST['weight']) &&
    isset($_POST['unity']) ||
    isset($_POST['type'])
) {

    $fRepo = new FoodRepository();


    if(isset($_POST['ingredient_exist'])){
        $ingredient = new Food([
            "id" => $_POST['ingredient_exist'],
            "name" => $_POST['name'],
            "weight" => $_POST['weight'],
            "type" => $_POST['type'],
            "unity" => $_POST['unity']
        ]);

        $res = $fRepo->updateFood($ingredient);

    }
    else{
        $ingredient = new Food([
            "id" => 0,
            "name" => $_POST['name'],
            "weight" => $_POST['weight'],
            "type" => $_POST['type'],
            "unity" => $_POST['unity']
        ]);

        $res = $fRepo->addFood($ingredient);
    }


    if ($res){
        new JsonReturn(JsonReturn::SUCCESS,"Ingredient created",201,$ingredient);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Error creating Ingredient",400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
}
