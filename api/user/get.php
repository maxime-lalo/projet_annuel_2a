<?php
if (isset($_GET['id'])){
    $uRepository = new UserRepository();
    $user = $uRepository->getOneById($_GET['id']);
    if ($user != null){
        new JsonReturn(JsonReturn::SUCCESS,"User found",200,$user);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User not found",404);
    }
}elseif(isset($_GET['email'])){
    $uRepository = new UserRepository();
    $user = $uRepository->getOneByEmail($_GET['email']);
    if ($user != null){
        new JsonReturn(JsonReturn::SUCCESS,"User found",200,$user);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User not found",404);
    }
}elseif(isset($_GET['email']) && isset($_GET['password'])){
    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $userID = $authService->log($_POST['mail'],$_POST['password']);
    if ($userID != -1 && $userID != -2){
        $user = $uRepository->getOneByEmail($_GET['email']);
        if ($user != null){
            new JsonReturn(JsonReturn::SUCCESS,"User found",200,$user);
        }else{
            new JsonReturn(JsonReturn::ERROR,"User not found",404);
        }
    }else{
        new JsonReturn(JsonReturn::ERROR,"Wrong email or password",404);
    }
}
else{
    new JsonReturn(JsonReturn::ERROR,"Missing id argument in get",400);
}
