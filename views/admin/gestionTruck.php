<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
$tRepo = new FoodTruckRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?>
        <span class="float-right">
            <a href="#" class="mb-2 btn btn-success">
                <i class="fa fa-plus"></i> <?= translate("Ajouter un camion");?>
            </a>
        </span>
    </h1>
    <p class="lead">

    </p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= translate("ID");?></th>
                <th><?= translate("Marque");?></th>
                <th><?= translate("Modèle");?></th>
                <th><?= translate("Date d'enregistrement");?></th>
                <th><?= translate("Date du dernier checkup");?></th>
                <th><?= translate("Kilométrage");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $trucks = $tRepo->getAll();

            if ($trucks != null){
                foreach ($trucks as $truck){
                    ?>
                    <tr>
                        <td><?= $truck->getId();?></td>
                        <td><?= ""//$truck->getBrand();?></td>
                        <td><?= ""//$truck->getModel();?></td>
                        <td><?= date('d/m/Y',strtotime($truck->getDateRegister()));?></td>
                        <td><?= date('d/m/Y',strtotime($truck->getDateCheck()));?></td>
                        <td><?= $truck->getMileage();?></td>
                        <td>
                            <button class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>