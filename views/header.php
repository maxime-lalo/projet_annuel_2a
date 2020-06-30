<?php
require_once __DIR__ . "/../services/auth/AuthService.php";
require_once __DIR__ . "/../utils/database/DatabaseManager.php";
require_once __DIR__ . "/../services/SweetAlert.php";
require_once __DIR__ . "/../repositories/UserRepository.php";

$uRepo = new UserRepository();
if (!isset($_SESSION['user']) && isset($_COOKIE['user_id'])){
    $_SESSION['user'] = serialize($uRepo->getOneById($_COOKIE['user_id']));
}

if (isset($_SESSION['user'])){
    $user = unserialize($_SESSION['user']);
    if ($user->isWorker() && !$uRepo->hasLicense($user)){
        $path = $path = "/".implode("/", $explodedUrl).".php";
        if ($path != "/franchisee/payLicense.php"){
            header("Location: /franchisee/payLicense");
        }
    }
}
$url = explode("/",$_SERVER['REQUEST_URI']);
if (isset($_GET['pdf']) OR $url[count($url) - 1] == "deconnexion"){

}else{
    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    if(isset($_COOKIE['user_id'])){
        $user = $authService->getUserFromId($_COOKIE["user_id"]);
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="descriptison">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="/public/assets/img/favicon.png" rel="icon">
        <link href="/public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/public/assets/vendor/icofont/icofont.min.css" rel="stylesheet">
        <link href="/public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/public/assets/vendor/venobox/venobox.css" rel="stylesheet">
        <link href="/public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="/public/assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="/public/assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" rel="stylesheet"/>
        <!-- Template Main CSS File -->
        <link href="/public/assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="/public/assets/vendor/jquery/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/8c58d132fd.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </head>
    <body>
    <header id="header" class="fixed-top d-flex align-items-center" style="background: rgba(2, 5, 161, 0.91)">
        <div class="container d-flex align-items-center">

            <div class="logo mr-auto">
                <h1 class="text-light"><a href="/<?= LANG;?>"><span>Driv'N'Cook</span></a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
            </div>
            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="/<?= LANG;?>"><?= translate("Accueil");?></a></li>
                    <?php
                    if (!isset($_COOKIE["user_id"])) {
                        ?>
                        <li class="drop-down"><a href="#"><?= translate("Inscription");?></a>
                            <ul>
                                <li><a href="/<?= LANG;?>/compte/inscriptionFranchise"><?= translate("Franchisé");?></a></li>
                                <li><a href="/<?= LANG;?>/compte/inscriptionClient"><?= translate("Client");?></a></li>
                            </ul>
                        </li>
                        <li><a href="/<?= LANG; ?>/compte/connexion"><?= translate("Connexion"); ?></a></li>
                        <?php
                    } else {
                        if ($user->isAdmin()){
                            ?>
                            <li class="drop-down"><a href="#"><?= translate("Gestion franchisés"); ?></a>
                                <ul>
                                    <li><a href="/<?= LANG; ?>/admin/truck/gestionTruck"><?= translate("Gestion camions"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/gestionWarehouse"><?= translate("Gestion entrepôts"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/manageFranchisees"><?= translate("Gestion des franchisés"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/truck/manageBreakdowns"><?= translate("Gestion des pannes"); ?></a></li>
                                </ul>
                            </li>
                            <?php
                        }elseif($user->isWorker()){
                            ?>
                            <li class="drop-down"><a href="#"><?= translate("Espace franchisés"); ?></a>
                                <ul>
                                    <li><a href="/<?= LANG; ?>/franchisee/truck"><?= translate("Gestion camion"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/franchisee/order/new"><?= translate("Faire une commande"); ?></a></li>
                                </ul>
                            </li>
                            <?php
                        }elseif($user->isClient()){
                            ?>
                            <li class="drop-down"><a href="#"><?= translate("Espace client");?></a>
                                <ul>
                                    <li><a href="/<?= LANG;?>/client/degustation"><?= translate("Mes dégustations");?></a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li class="drop-down"><a href="#"><?= translate("Mon Compte"); ?></a>
                            <ul>
                                <li><a href="/<?= LANG; ?>/compte/compte"><?= translate("Profil"); ?></a></li>
                                <li><a href="/<?= LANG; ?>/compte/deconnexion"><?= translate("Déconnexion"); ?></a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="drop-down"><a href="#"><?= translate("Langage");?></a>
                        <ul>
                            <?php
                            foreach (POSSIBLE_LANGUAGES as $key => $value) {
                                if ($value != LANG) {
                                    $actualUrl = $_SERVER['REQUEST_URI'];
                                    $actualUrl = explode('/',$actualUrl);
                                    if (in_array($actualUrl[1], POSSIBLE_LANGUAGES)) {
                                        $actualUrl[1] = $value;
                                        unset($actualUrl[0]);
                                    }else{
                                        $actualUrl[0] = $value;
                                    }
                                    ?>
                                    <li><a href="/<?= implode('/',$actualUrl);?>"><?= strtoupper($value);?></a></li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
}
