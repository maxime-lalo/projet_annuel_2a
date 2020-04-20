<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';

if(isset($_POST['mail']) && isset($_POST['password'])){

    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->log($_POST['mail'],$_POST['password']);
    if(isset($user)){
        if(isset($_POST['check']))
            setcookie('user_id', $user, time()+2592000);

        else
        setcookie('user_id', $user);

        header('Location: Compte');
    }
    else {
        header('Location: connexion');
    }



}