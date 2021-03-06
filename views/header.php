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

$urlWithoutParameters = explode("?",$url[count($url) - 1]);
if (isset($_GET['pdf']) OR $url[count($url) - 1] == "deconnexion" OR ($urlWithoutParameters[0] == "connexion" && $_SERVER['REQUEST_METHOD'] == "POST")){

}else{
    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    if(isset($_COOKIE['user_id'])){
        $user = $authService->getUserFromId($_COOKIE["user_id"]);
    }
    $uri = $_SERVER['REQUEST_URI'];
    $page = "home";
    
    if( strpos($uri,"/compte/inscriptionFranchise") != false || strpos($uri,"/compte/inscriptionClient") != false  ){
        $page = "signup";
    }
    if( strpos($uri,"/compte/connexion") != false ){
        $page = "signin";
    }
    if( strpos($uri,"/admin/truck/gestionTruck") != false || 
        strpos($uri,"/admin/warehouse/manageWarehouses") != false || strpos($uri,"/admin/manageNewFranchisee") != false || 
        strpos($uri,"/admin/manageFranchisees") != false){
        $page = "admin";
    }
    if( strpos($uri,"/franchisee/truck") != false || strpos($uri,"/franchisee/order/new") != false || strpos($uri,"/franchisee/clientOrder/today") != false || strpos($uri,"/franchisee/order/history") != false ){
        $page = "worker";
    }
    if( strpos($uri,"/client/order/trucks") != false || strpos($uri,"/client/order/history") != false || strpos($uri,"/client/trucksMap") != false || strpos($uri,"client/order/new") != false || strpos($uri,"/client/degustation") != false || strpos($uri,"/client/order/pay") != false ){
        $page = "client";
    }
    if( strpos($uri,"/compte/compte") != false || strpos($uri,"/compte/deconnexion") != false){
        $page = "account";
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

        <!-- Librairie select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

        <!-- Bootstrap table -->
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
        <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
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
                    <li <?= ($page == "home")?'class="active"': "" ?>><a href="/<?= LANG;?>"><?= translate("Accueil");?></a></li>
                    <?php
                    if (!isset($_COOKIE["user_id"])) {
                        ?>
                        <li class="drop-down <?= ($page == "signup")? 'active': "" ?>"><a href="#"><?= translate("Inscription");?></a>
                            <ul>
                                <li><a href="/<?= LANG;?>/compte/inscriptionFranchise"><?= translate("Franchisé");?></a></li>
                                <li><a href="/<?= LANG;?>/compte/inscriptionClient"><?= translate("Client");?></a></li>
                            </ul>
                        </li>
                        <li <?= ($page == "signin")?'class="active"': "" ?>><a href="/<?= LANG; ?>/compte/connexion"><?= translate("Connexion"); ?></a></li>
                        <?php
                    } else {
                        if ($user->isAdmin()){
                            ?>
                            <li class="drop-down <?= ($page == "admin")? 'active': "" ?>"><a href="#"><?= translate("Gestion"); ?></a>
                                <ul>
                                    <li><a href="/<?= LANG; ?>/admin/truck/gestionTruck"><?= translate("Gestion camions"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/warehouse/manageWarehouses"><?= translate("Gestion entrepôts"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/manageFranchisees"><?= translate("Gestion des franchisés"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/truck/manageBreakdowns"><?= translate("Gestion des pannes"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/menu/listofMenu"><?= translate("Gestion des menus"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/ca/caFFW"><?= translate("Gestion du chiffre d'affaire"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/recipe/view"><?= translate("Gestion des recettes"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/admin/ingredient/listofIngredient"><?= translate("Gestion des ingredients"); ?></a></li>
                                </ul>
                            </li>
                            <?php
                        }elseif($user->isWorker()){
                            ?>
                            <li class="drop-down <?= ($page == "worker")? 'active': "" ?>"><a href="#"><?= translate("Espace franchisés"); ?></a>
                                <ul>
                                    <li><a href="/<?= LANG; ?>/franchisee/clientOrder/today"><?= translate("Commandes du jour"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/franchisee/truck"><?= translate("Gestion camion"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/franchisee/order/new"><?= translate("Faire une commande"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/franchisee/events/index"><?= translate("Gérer mes évènements"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/franchisee/viewStock"><?= translate("Mon stock"); ?></a></li>
                                </ul>
                            </li>
                            <?php
                        }elseif($user->isClient()){
                            ?>
                            <li class="drop-down <?= ($page == "client")? 'active': "" ?>"><a href="#"><?= translate("Espace client");?></a>
                                <ul>
                                    <li><a href="/<?= LANG;?>/client/events"><?= translate("Mes évènements");?></a></li>
                                    <li><a href="/<?= LANG; ?>/client/order/trucks"><?= translate("Une petite faim ?"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/client/order/history"><?= translate("Historique commandes"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/client/trucksMap"><?= translate("Tous nos FoodTruck"); ?></a></li>
                                    <li><a href="/<?= LANG; ?>/client/showFidelityCard?pdf=true" target="_blank"><?= translate("Imprimer ma carte fidélité"); ?></a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li class="drop-down <?= ($page == "account")? 'active': "" ?>"><a href="#"><?= translate("Mon Compte"); ?></a>
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
