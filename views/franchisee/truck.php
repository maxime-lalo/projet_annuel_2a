<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";
$tRepository = new FoodTruckRepository();
$uRepository = new UserRepository();
$truck = $tRepository->getFromUserId($user->getId());
if ($truck != null){
    $isOnBreakdown = $tRepository->isOnBreakdown($truck);
}

if (isset($_GET['setBreakdown'])){
    if (!$isOnBreakdown){
        $tRepository->setBreakdown($tRepository->getOneById($_GET['setBreakdown']));
    }
}elseif(isset($_GET['cancelBreakdown'])){
    $tRepository->cancelBreakdown($tRepository->getOneById($_GET['cancelBreakdown']));
}
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Mon camion");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Mon camion");?>
        <?php
        if ($truck != null){
            ?>
            <span class="float-right">
                <a href="truckHistory" class="btn btn-primary mb-2">
                    <i class="fa fa-history"></i> <?= translate("Historique des pannes");?>
                </a>
            </span>
            <?php
        }
        ?>
    </h1>
    <?php

    if ($truck != null){
        if ($isOnBreakdown){
            ?>
            <p class="lead" style="color:red"><?= translate("Votre camion est actuellement marqué comme en panne");?></p>
            <?php
        }else{
            ?>
            <p class="lead"><?= translate("Ce camion vous est actuellement attribué");?></p>
            <?php
        }
        ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th><?= translate("Marque");?></th>
                <th><?= translate("Modèle");?></th>
                <th><?= translate("Enregistré le");?></th>
                <th><?= translate("Vérifié pour la dernière fois le");?></th>
                <th><?= translate("Kilomètrage");?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $truck->getBrand();?></td>
                <td><?= $truck->getModel();?></td>
                <td><?= timestampFormat($truck->getDateRegister());?></td>
                <td><?= timestampFormat($truck->getDateCheck());?></td>
                <td><?= $truck->getMileage();?></td>
            </tr>
            </tbody>
        </table>
        <?php
        if (!$isOnBreakdown){
            ?>
            <a class="btn btn-danger" href="?setBreakdown=<?= $truck->getId();?>"><?= translate("Déclarer une panne");?></a>
            <?php
        }
    }else{
        ?>
        <p class="lead"><?= translate("Aucun camion ne vous a encore été attribué");?></p>
        <?php
    }
    ?>
</div>