<?php
$jsonText = file_get_contents('php://input');
$json = json_decode($jsonText, true);
//echo count($json);
if (isset($json['firstname']) && isset($json['lastname']) &&
    isset($json['password']) && isset($json['email']) && isset($json['phone'])
    && isset($json['city']) && isset($json['street_name']) && isset($json['street_number'])) {

    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->subscribeClient($json['firstname'], $json['lastname'], $json['password'], $json['email'], $json['phone']
        , $json['street_name'], $json['street_number'], $json['city']);
    if ($user === null) {
        echo json_encode(["status" => "error", "error" => "Email already exists"]);
        http_response_code(400);
    }else{
        $newUserFile = fopen("../../user_creation/new_user/".$json['firstname'].$json['lastname'].".json", "w");
        fwrite($newUserFile, $jsonText);
        fclose($newUserFile);
        echo json_encode(["status" => "created", "error" => "", "info"=>"User created"]);
        http_response_code(201);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}