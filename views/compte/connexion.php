<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';
if(isset($_POST['mail']) && isset($_POST['password'])){
    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->log($_POST['mail'],$_POST['password']);
    if($user >= 0){
        if(isset($_POST['check'])) {
            session_start();
            setcookie('user_id', $user, time() + 2592000,"/");

        }else{
            setcookie('user_id', $user,time() + 3600,"/");
            header('Location: compte');
        }
    }else{
        if ($user == -1){
            new SweetAlert(SweetAlert::ERROR,"Erreur","E-mail ou mot de passe incorrect");
        }elseif($user == -2){
            new SweetAlert(SweetAlert::ERROR,"Erreur","Votre compte n'est pas encore activé");
        }
    }



}
?>
<form method="post">
    <br>
    <div class="container-fluid txt-container">
        <h3 align="center"><?= translate("Connexion");?></h3>
        <br>
    <div class="form-group">
        <label for="mail">Email address</label>
        <input required type="email" class="form-control" name="mail" aria-describedby="emailHelp">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" name="password">
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="check">
        <label class="form-check-label" for="check">Check me out</label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
