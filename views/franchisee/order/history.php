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
</div>