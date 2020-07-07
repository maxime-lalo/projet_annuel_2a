<?php
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../../repositories/StockRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();
$sRepo = new StockRepository();
?>
<title><?= translate("Consulter le stock des franchisés");?> - <?= translate("Consulter le stock des franchisés");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Consulter le stock des franchisés");?>
        <span class="float-right">
            <a href="../manageFranchisees" class="btn btn-primary mb-2"><?= translate("Retour à la gestion des franchisés");?></a>
        </span>
    </h1>
    <?php
        $franchisee = $uRepo->getOneById($_GET['id']);
        if ($franchisee){
            ?>
            <p class="lead"><?= translate("Stock du franchisé");?> : <?= $franchisee->getFirstName()." ".$franchisee->getLastName();?></p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><?= translate("Article");?></th>
                        <th><?= translate("Type");?></th>
                        <th><?= translate("Stock");?></th>
                        <th><?= translate("Poids unitaire");?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stock = $sRepo->getAllByFranchisee($franchisee);
                    if ($stock){
                        /* @var $food Food */
                        foreach ($stock as $food){
                            ?>
                            <tr>
                                <td><?= $food->getName();?></td>
                                <td><?= $food->getType();?></td>
                                <td><?= $food->getQuantity();?></td>
                                <td><?= $food->getWeight().$food->getUnity();?></td>
                            </tr>
                            <?php
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="3"><?= translate("Vous n'avez pas encore passé de commande");?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
    }
    ?>
</div>
