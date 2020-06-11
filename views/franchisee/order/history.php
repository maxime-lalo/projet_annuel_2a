<?php
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();

// On récupère l'utilisateur courant
$user = $uRepo->getOneById($_COOKIE['user_id']);
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Historique des commandes");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Historique des commandes");?>
        <span class="float-right">
            <a href="new" class="btn btn-primary mb-2"><i class="fas fa-box"></i> <?= translate("Passer une nouvelle commande");?></a>
        </span>
    </h1>
    <?php
    if (isset($_GET['id'])){
        $order = $fORepo->getOneById($_GET['id']);
        ?>
        <div class="row justify-content-center mb-2">
            <a href="history" class="btn btn-primary mr-2"><?= translate("Retour à l'historique");?></a>
            <a href="history" class="btn btn-primary"><i class="fas fa-print"></i> <?= translate("Imprimer le bon de commande");?></a>
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
        ?>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th><?= translate("N° de commande");?></th>
                <th><?= translate("Date");?></th>
                <th><?= translate("Nombre d'articles");?></th>
                <th><?= translate("Nombre d'articles manquants");?></th>
                <th><?= translate("Pourcentage");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $orders = $fORepo->getAllOrders($user);
            if ($orders){
                foreach ($orders as $order){
                    ?>
                    <tr class="clickable-row" data-href="?id=<?= $order->getId();?>">
                        <td><?= $order->getId();?></td>
                        <td><?= $order->getDate()->format("d/m/Y H:i");?></td>
                        <td><?= count($order->getFoods());?></td>
                        <td><?= count($order->getMissing());?></td>
                        <td><?= $order->getPercentage();?></td>
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