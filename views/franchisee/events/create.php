<?php
require_once __DIR__ . "/../../../repositories/EventRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";

$eRepo = new EventRepository();
$uRepo = new UserRepository();
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Créer un évènement");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Créer un évènement");?>
        <span class="float-right">
            <a href="index" class="btn btn-primary mb-2"><i class="fa fa-history"></i> <?= translate("Liste de mes évènement");?></a>
        </span>
    </h1>
</div>
