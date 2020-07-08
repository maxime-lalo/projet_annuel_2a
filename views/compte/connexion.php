<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../../repositories/UserRepository.php';

if(isset($_POST['mail']) && isset($_POST['password'])){
    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->log($_POST['mail'],$_POST['password']);

    $uRepo = new UserRepository();

    if($user >= 0){
        $userObj = $uRepo->getOneById($user);
        $_SESSION['user'] = serialize($userObj);
        $cookieTime = isset($_POST['check']) ? 2592000:3600;
        setcookie('user_id', $user, time() + $cookieTime,"/");
        header('Location: compte');
    }else{
        if ($user == -1){
            header('Location: connexion?error=incorrectPass');
        }elseif($user == -2){
            header('Location: connexion?error=notActivated');

        }
    }
}

if (isset($_GET['error'])){
    if ($_GET['error'] == "notActivated"){
        new SweetAlert(SweetAlert::ERROR,"Erreur","Votre compte n'est pas encore activé");
    }elseif($_GET['error'] == "incorrectPass"){
        new SweetAlert(SweetAlert::ERROR,"Erreur","E-mail ou mot de passe incorrect");
    }
}
?>
<div class="container">
    <h1 id="page-title"><?= translate("Connexion");?></h1>
    <form method="post">
        <br>
        <div class="container-fluid txt-container">
            <div class="form-group">
                <label for="mail"><?= translate("Adresse mail");?></label>
                <input required type="email" class="form-control" name="mail" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <label for="password"><?= translate("Mot de passe");?></label>
                <input required type="password" class="form-control" name="password">
            </div>
            <div class="form-check float-left">
                <input type="checkbox" class="form-check-input" name="check" id="check">
                <label class="form-check-label" for="check">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= translate("Rester connecté");?></label>
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-primary"><?= translate("Se connecter");?></button>
            </div>
        </div>
    </form>
</div>
