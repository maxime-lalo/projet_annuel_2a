<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";
$tRepository = new FoodTruckRepository();
$uRepository = new UserRepository();
$truck = $tRepository->getFromUserId($user->getId());

if (isset($_GET['pay']) && is_numeric($_GET['pay'])){
    $tRepository->payBreakdownBill($_GET['pay']);
}
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Historique des pannes");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Historique des pannes");?>
        <span class="float-right">
            <a href="truck" class="btn btn-primary mb-2">
                <i class="fas fa-truck"></i> <?= translate("Mon camion");?>
            </a>
        </span>
    </h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= translate("Date");?></th>
                <th><?= translate("État");?></th>
                <th><?= translate("Description");?></th>
                <th><?= translate("Prix");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $states = [
                0 => translate("Panne déclarée"),
                1 => translate("Panne prise en compte"),
                2 => translate("Panne traitée et à payer"),
                3 => translate("Panne terminée")
            ];
            $breakdowns = $tRepository->getBreakdownHistory($truck);
            foreach ($breakdowns as $breakdown){
                ?>
                <tr>
                    <td><?= date("d/m/Y H:i",strtotime($breakdown['date']));?></td>
                    <td><?= $states[$breakdown['state']];?></td>
                    <td><?= $breakdown['description'] == null ? "-":$breakdown['description'];?></td>
                    <td><?= $breakdown['price'] == null ? "-":$breakdown['price'];?></td>
                    <td>
                        <?php
                        if ($breakdown['state'] == 2){
                            ?>
                            <a href="?pay=<?= $breakdown['id'];?>" class="btn btn-primary"><?= translate("Payer la facture");?></a>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>