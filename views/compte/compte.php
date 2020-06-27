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
            <div class="col-sm-10"><h1><?= $user->getFirstname() . ' ' . $user->getLastname(); ?></h1></div>
            <div class="col-sm-2"><a href="#" class="pull-right">
                <img title="profile image" class="img-circle img-responsive" src="http://www.gravatar.com/avatar/28fd20ccec6865e2d5f0e1f4446eb7bf?s=100"></a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"><!--left col-->


                <div class="text-center">
                    <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail"
                         alt="avatar">
                    <h6><?= translate("Changé de photo de profil")?></h6>
                    <input type="file" class="text-center center-block file-upload">
                </div>
                </hr><br>

            </div><!--/col-3-->
            <div class="col-sm-9">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#settings"><?= translate("Informations")?></a></li>
                    <?php if ($user->isWorker()) { ?>
                        <li><a data-toggle="tab" href="#camions"><?= translate("Camion")?></a></li>
                        <li><a data-toggle="tab" href="#entrepots"><?= translate("Entrepot")?></a></li>
                    <?php } ?>
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
                                    <?= translate("Annulé modifications")?>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr>

                    </div><!--/tab-pane-->

                    <div class="tab-pane" id="camions">
                        <hr>
                        <?php 
                        if (!isset($truck)) {
                            echo 'pas de camion rattaché';
                        }else{ 
                            ?>
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="mileage"><h4><?= translate("Kilométrage")?></h4></label>
                                    <input required disabled type="text" class="form-control" name="mileage"
                                           value="<?= $truck->getMileage(); ?>">
                                </div>
                                <div class="col-xs-6">
                                    <label for="register"><h4><?= translate("Date d'enregistrement'")?></h4></label>
                                    <input required disabled type="text" class="form-control" name="register"
                                           value="<?= $truck->getDateRegister(); ?>">
                                </div>

                                <div class="col-xs-6">
                                    <label for="lastcheck"><h4><?= translate("Date du dernier checkup")?></h4></label>
                                    <input required disabled type="text" class="form-control" name="lastcheck"
                                           value="<?= $truck->getDateCheck(); ?>">
                                </div>
                            </div>
                        <?php 
                        } 
                        ?>
                    </div><!--/tab-pane-->
                    <div class="tab-pane" id="entrepots">
                        <hr>
                        <?php if (!isset($warehouse)) {
                            echo 'pas d\'entrepot rattaché';
                        }
                        else{ ?>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="name"><h4><?= translate("Nom")?></h4></label>
                                <input required disabled type="text" class="form-control" name="name"
                                       value="<?= $warehouse->getName(); ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="city"><h4><?= translate("Ville")?></h4></label>
                                <input required disabled type="text" class="form-control" name="city"
                                       value="<?= $warehouse->getCity(); ?>">
                            </div>

                            <div class="col-xs-6">
                                <label for="street_name"><h4><?= translate("Rue")?></h4></label>
                                <input required disabled type="text" class="form-control" name="street_name"
                                       value="<?= $warehouse->getStreetName(); ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="street_number"><h4><?= translate("Numéro")?></h4></label>
                                <input required disabled type="text" class="form-control" name="street_number"
                                       value="<?= $warehouse->getStreetNumber(); ?>">
                            </div>
                        </div>
                    <?php } ?>
                    </div>
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
        <script>
            $(document).ready(function () {
                var readURL = function (input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('.avatar').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }


                $(".file-upload").on('change', function () {
                    readURL(this);
                });
            });
        </script>