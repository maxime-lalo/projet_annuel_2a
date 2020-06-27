<?php
$json = json_decode(file_get_contents('php://input'), true);

$uRepo = new UserRepository();
if(isset($json['id']) && isset($json['firstname']) && 
   isset($json['lastname']) && isset($json['password']) && 
   isset($json['email']) && isset($json['phone']) && 
   isset($json['street_name']) && isset($json['street_number']) && 
   isset($json['city']) && isset($json['is_client']) && 
   isset($json['is_worker']) && isset($json['street_number']) && 
   isset($json['is_admin']) && isset($json['points'])){
    $userTest = $uRepo->getOneById($json['id']);
    if($userTest != null){
        if(isset($json['truck'])){
            if($json['truck'] != "null"){
                $json += ['food_truck_id'=> $json['truck']['id']]; 
                unset($json['truck']);
            }else{
                unset($json['truck']);
            }
        }
        if(isset($json['warehouse'])){
            if($json['warehouse'] != "null"){
                $json += ['warehouse_id'=> $json['warehouse']['id']]; 
                unset($json['warehouse']);
            }else{
                unset($json['warehouse']);
            }
        }
        $user = new User($json);
        if ($uRepo->update($user)){
            new JsonReturn(JsonReturn::SUCCESS,"User updated",200,$user);
        }else{
            new JsonReturn(JsonReturn::SUCCESS,"User not modified",304, $user);
        }
    }else{
        new JsonReturn(JsonReturn::ERROR,"User not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}