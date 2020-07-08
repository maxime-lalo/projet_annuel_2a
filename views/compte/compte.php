<?php
require_once __DIR__ . "/../../services/auth/AuthService.php";
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../repositories/WarehouseRepository.php";
require_once __DIR__ . "/../../utils/database/DatabaseManager.php";

$manager = new DatabaseManager();
$authService = new AuthService($manager);
$truckService = new FoodTruckRepository();
$warehouseService = new WarehouseRepository();
$user_id = $_COOKIE["user_id"];
$error = '';
$user = $authService->getUserFromId($user_id);

if (isset($_POST['submit']) && isset($_POST['firstname']) && isset($_POST['lastname'])
    && isset($_POST['email']) && isset($_POST['phone'])
    && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['number'])) {
    $user = $authService->updateUser($_POST['firstname'], $_POST['lastname'], $_POST['email'],
        $_POST['phone'], $_POST['address'], $_POST['number'], $_POST['city'], $user_id);
}
else if(isset($_POST['conf_mdp']) && isset($_POST['submitMdp']) && isset($_POST['mdp'])){
    if($_POST['conf_mdp'] == $_POST['mdp'])
    $user = $authService->updateMdpUser($user_id , $_POST['mdp']);
    else {
        $error = "Les mot de passe ne correspondent pas";
    }
}
else {
    if ($user->isWorker()) {
        $truck = $truckService->getFromUserId($user_id);
        $warehouse = $warehouseService->getFromUserId($user_id);
    }
}
?>
<div class="container-fluid">
    <div class="container bootstrap snippet">
        <div class="row">
            <div class="col-sm-10">
             <h1 style="color: #721c24"><?=$error;?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-10"><h1><?= $user->getFirstname() . ' ' . $user->getLastname(); ?></h1></div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#settings"><?= translate("Informations")?></a></li>
                    <li><a data-toggle="tab" href="#mdp"><?= translate("Mot de passe")?></a></li>
                </ul>


                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <hr>
                        <form class="form" action="compte" method="post"
                              id="registrationForm">
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="firstname"><h4><?= translate("Prénom")?></h4></label>
                                    <input required type="text" class="form-control" name="firstname"
                                           value="<?= $user->getFirstname(); ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="lastname"><h4><?= translate("Nom")?></h4></label>
                                    <input required type="text" class="form-control" name="lastname"
                                           value="<?= $user->getLastname(); ?>">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="phone"><h4><?= translate("Téléphone")?></h4></label>
                                    <input required type="tel" class="form-control" name="phone"
                                           pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$"
                                           value="<?= $user->getPhone(); ?>">
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <label for="email"><h4><?= translate("Email")?></h4></label>
                                <input required type="email" class="form-control" name="email"
                                       value="<?= $user->getEmail(); ?>">
                            </div>

                            <div class="col-xs-6">
                                <label for="address"><h4><?= translate("Rue")?></h4></label>
                                <input required type="text" class="form-control" name="address"
                                       value="<?= $user->getStreetName(); ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="number"><h4><?= translate("Numéro")?></h4></label>
                                <input required type="number" class="form-control" name="number"
                                       value="<?= $user->getStreetNumber(); ?>">
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="city"><h4><?= translate("Ville")?></h4></label>
                                    <input required type="text" class="form-control" name="city"
                                           value="<?= $user->getCity(); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-lg btn-success" type="submit" name="submit"><i
                                                class="glyphicon glyphicon-ok-sign"></i> <?= translate("Enregistrer modifications")?>
                                    </button>
                                    <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i>
                                    <?= translate("Annuler les modifications")?>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr>

                    </div><!--/tab-pane-->
                    <div class="tab-pane" id="mdp">
                        <hr>
                        <form method="post" action="compte" class="form">
                        <div class="form-group">
                           <div class="col-xs-6">
                               <label for="mdp"><h4><?= translate("Nouveau mot de passe")?></h4></label>
                               <input required type="password" class="form-control" name="mdp">
                           </div>
                        </div>
                        <div class="form-group">
                        <div class="col-xs-6">
                            <label for="conf_mdp"><h4><?= translate("Confirmer mot de passe")?></h4></label>
                            <input required type="password" class="form-control" name="conf_mdp">
                        </div>
                        </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                        <button class="btn btn-lg btn-success" type="submit" name="submitMdp"><i
                                    class="glyphicon glyphicon-ok-sign"></i> <?= translate("Enregistrer")?>
                        </button>
                        </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>