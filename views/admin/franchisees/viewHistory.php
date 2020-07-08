<?php
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();
?>
<title><?= translate("Consulter le stock des franchisés");?> - <?= translate("Consulter l'historique de commande des franchisés");?></title>
<div class="container">
    <h1 id="page-title"><?= translate("Espace Admin");?> - <?= translate("Consulter l'historique de commande des franchisés");?></h1>
    <?php
    if (isset($_GET['idCmd'])){
        $order = $fORepo->getOneById($_GET['idCmd']);
        ?>
        <div class="row justify-content-center mb-2">
            <button class="btn btn-primary" onclick="window.history.back();"><?= translate("Retour à l'historique");?></button>
            <a href="../../franchisee/order/generatePdf?pdf=true&id=<?= $order->getId();?>" class="btn btn-primary ml-2" target="blank"><i class="fas fa-print"></i> <?= translate("Imprimer le bon de commande");?></a>
        </div>
        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <th colspan="4"><?= translate("Commande n°");?> : <?= $order->getId();?></th>
            </tr>
            <tr>
                <th><?= translate("Date");?></th>
                <th><?= translate("Nombre d'articles fournis");?></th>
                <th><?= translate("Nombre d'articles manquants");?></th>
                <th><?= translate("Pourcentage");?></th>
            </tr>
            <tr>
                <td><?= $order->getDate()->format("d/m/Y H:i");?></td>
                <td><?= count($order->getFoods());?></td>
                <td><?= count($order->getMissing());?></td>
                <td><?= $order->getPercentage();?></td>
            </tr>
            <tr>
                <th colspan="4"><?= translate("Articles fournis");?></th>
            </tr>
            <tr>
                <th colspan="2"><?= translate("Article");?></th>
                <th><?= translate("Type");?></th>
                <th><?= translate("Quantité fournie");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            /* @var $food Food */
            foreach($order->getFoods() as $food){
                ?>
                <tr>
                    <td colspan="2"><?= $food->getName();?></td>
                    <td><?= $food->getType();?></td>
                    <?php
                    if (empty($food->getUnity())){
                        ?>
                        <td><?= $food->getQuantity();?></td>
                        <?php
                    }else{
                        ?>
                        <td><?= $food->getQuantity();?> * <?= $food->getWeight().$food->getUnity();?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }

            ?>
            <tr>
                <th colspan="4" style="text-align:center">Articles manquants</th>
            </tr>
            <?php
            foreach($order->getMissing() as $food){
                ?>
                <tr>
                    <td colspan="2"><?= $food->getName();?></td>
                    <td><?= $food->getType();?></td>
                    <td><?= ($food->getQuantity() * $food->getWeight()).$food->getUnity();?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }else{
        $franchisee = $uRepo->getOneById($_GET['id']);
        if ($franchisee){
            ?>
            <p class="lead"><?= translate("Historique de commande du franchisé");?> : <?= $franchisee->getFirstName()." ".$franchisee->getLastName();?></p>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?= translate("N° de commande");?></th>
                    <th><?= translate("Date");?></th>
                    <th><?= translate("Nombre d'articles");?></th>
                    <th><?= translate("Nombre d'articles manquants");?></th>
                    <th><?= translate("Pourcentage");?></th>
                    <th><?= translate("Status");?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $orders = $fORepo->getAllOrders($franchisee);
                if ($orders){
                    /* @var $order FranchiseeOrder */
                    foreach ($orders as $order){
                        ?>
                        <tr class="clickable-row" data-href="?idCmd=<?= $order->getId();?>">
                            <td><?= $order->getId();?></td>
                            <td><?= $order->getDate()->format("d/m/Y H:i");?></td>
                            <td><?= count($order->getFoods());?></td>
                            <td><?= count($order->getMissing());?></td>
                            <td><?= $order->getPercentage();?></td>
                            <td><?= translate(ORDER_STATUS[$order->getStatus()]);?></td>
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
        }else{
            echo translate("Veuillez sélectionner un franchisé valide");
        }
    }
    ?>
</div>
