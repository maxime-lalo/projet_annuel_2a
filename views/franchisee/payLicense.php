<?php
require_once __DIR__ . "/../../repositories/UserRepository.php";
$uRepo = new UserRepository();
if (isset($_POST['cardNumber']) &&
    isset($_POST['expirationDate']) &&
    isset($_POST['cvc'])){
    if (!$uRepo->hasLicense($user)){
        $license = $uRepo->payLicense($user);
        if ($license){
            new SweetAlert(SweetAlert::SUCCESS,"Succès","Le paiement a bien été accepté, voici votre numéro de licence : ".$license);
        }
    }
}else{
    if (!$uRepo->hasLicense($user)){
        new SweetAlert(SweetAlert::WARNING,"Règlement de la license","Pour accéder à toutes les fonctionnalités du site, vous devez d'abord régler votre license");
    }
}
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Régler ma licence");?></title>
<div class="container">
    <h1 id="page-title"><?= translate("Régler la licence");?></h1>
    <?php
    if ($uRepo->hasLicense($user)){
        ?>
        <p class="lead">
            <?= translate("Votre licence est déjà réglée, voici son identifiant : ") . $uRepo->getLicense($user);?>
        </p>
        <?php
    }else{
        ?>
        <p class="lead">
            <?= translate("La première étape est désormais remplie, votre CV a été accepté au sein de notre franchise, afin de pouvoir accéder au site il vous faut désormais régler la license d'un montant de 50 000€");?>
        </p>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <label for="cardNumber"><?= translate("Numéro de carte");?></label>
                    <input type="number" name="cardNumber" class="form-control" id="cardNumber">
                </div>
                <div class="col">
                    <label for="expirationDate"><?= translate("Date d'expiration");?></label>
                    <input type="date" name="expirationDate" class="form-control" id="expirationDate">
                </div>
                <div class="col">
                    <label for="cvc"><?= translate("CVC");?></label>
                    <input type="number" name="cvc" class="form-control" id="cvc" max="999" min="0">
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-primary">
        </form>
        <?php
    }
    ?>
</div>