<div class="container-fluid txt-container">
<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';

if(isset($_POST['mail']) && isset($_POST['password'])){

    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->log($_POST['mail'],$_POST['password']);
    if(isset($user)){
        var_dump($authService->getUserFromId($user));
    }



}