<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
$tRepository = new FoodTruckRepository();
if (isset($_GET['setBreakdown'])){
    $tRepository->setBreakdown($tRepository->getOneById($_GET['setBreakdown']));
}elseif(isset($_GET['cancelBreakdown'])){
    $tRepository->cancelBreakdown($tRepository->getOneById($_GET['cancelBreakdown']));
}
?>
<title><?= translate("Espace franchisé - Mon camion");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé - Mon camion");?>
    </h1>
    <?php
    $truck = $user->getTruck();
    $isOnBreakdown = $tRepository->isOnBreakdown($truck);
    if ($truck != null){
        if ($isOnBreakdown){
            ?>
            <p class="lead"><?= translate("Votre camion est actuellement marqué comme en panne");?></p>
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
        }else{
            ?>
            <a class="btn btn-success" href="?cancelBreakdown=<?= $truck->getId();?>"><?= translate("Marquer le camion comme réparé");?></a>
            <?php
        }
    }else{
        ?>
        <p class="lead"><?= translate("Aucun camion ne vous a encore été attribué");?></p>
        <?php
    }
    ?>
</div>