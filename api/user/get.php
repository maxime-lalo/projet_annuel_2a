<?php
if (isset($_GET['id'])){
    $uRepository = new UserRepository();
    $user = $uRepository->getOneById($_GET['id']);
    if ($user != null){
        new JsonReturn(JsonReturn::SUCCESS,"User found",200,$user);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User not found",404);
    }
}elseif(isset($_GET['email_like'])){
    $uRepository = new UserRepository();
    $users = $uRepository->getAllByEmailLike($_GET['email_like']);
    if($users){
        $json = "{\"status\":\"success\",\"Users\":[";

            for($i = 0; $i < count($users)-1; $i++){
                $json .= json_encode($users[$i]).",";
            }
            $json .= json_encode($users[count($users)-1]);
        
            $json .= "]}";
            
            echo $json;
            http_response_code(200);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Email not found",404);
    }
}elseif(isset($_GET['email']) && isset($_GET['password'])) {
    $uRepository = new UserRepository();
    $user = $uRepository->getOneByEmail($_GET['email']);
    if ($user != null) {
        $hashed = hash('sha256', $_GET['password']);
        if($user->getPassword() == $hashed) {
            new JsonReturn(JsonReturn::SUCCESS, "User found", 200, $user);
        }
    } else {
        new JsonReturn(JsonReturn::ERROR, "User not found", 404);
    }
}

elseif(isset($_GET['allUser'])) {
    $uRepository = new UserRepository();
    $user = $uRepository->getAll();
    if ($user != null) {
        $result = array();
        $result += ["status" => "success"];
        $result  += ["info" => "Users returned"];
        $result += ["Users" => $user];
        echo json_encode($result);
        http_response_code(200);
    }
    else new JsonReturn(JsonReturn::ERROR, "no user found", 404);
}


else{
    new JsonReturn(JsonReturn::ERROR,"Missing id argument in get",400);
}
