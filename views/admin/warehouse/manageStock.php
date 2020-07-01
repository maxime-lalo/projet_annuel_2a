<?php
require_once __DIR__ . "/../../../repositories/WarehouseRepository.php";
require_once __DIR__ . "/../../../repositories/FoodRepository.php";
$wRepo = new WarehouseRepository();
$fRepo = new FoodRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Modifier le stock d'un entrepôt");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Modifier le stock d'un entrepôt");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="manageWarehouses">
                <i class="fa fa-edit"></i> <?= translate("Gestion des entrepôts");?>
            </a>
        </span>
    </h1>
    <?php
    if (isset($_GET['id'])){
        $actualWarehouse = $wRepo->getOneById($_GET['id']);
        ?>
        <p class="lead"><?= translate("Entrepôt sélectionné")." : <b>".$actualWarehouse->getName()."</b>";?></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= translate("Id");?></th>
                    <th><?= translate("Article");?></th>
                    <th><?= translate("Taille article");?></th>
                    <th><?= translate("Stock");?></th>
                    <th><?= translate("Actions");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stock = $fRepo->getAllWithStock($actualWarehouse);
                foreach($stock as $item){
                    ?>
                    <tr>
                        <td><?= $item->getId();?></td>
                        <td><?= $item->getName();?></td>
                        <td><?= $item->getWeight() . $item->getUnity();?></td>
                        <td><input type="number" class="form-control" value="<?= $item->getQuantity();?>" id="article<?= $item->getId();?>"></td>
                        <td>
                            <button class="btn btn-success" data-toggle="tooltip" title="Valider les changements" onclick="updateStock(<?= $item->getId();?>, <?= $actualWarehouse->getId();?>)">
                                <i class="fa fa-check"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }else{
        ?>
        <p class="lead"><?= translate("Aucun entrepôt sélectionné, cliquez sur un entrepôt pour le sélectionner");?></p>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><?= translate("Id");?></th>
                    <th><?= translate("Nom");?></th>
                    <th><?= translate("Emplacement");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $allWarehouses = $wRepo->getAll();
                if ($allWarehouses != null){
                    foreach ($allWarehouses as $warehouse){
                        ?>
                        <tr class="clickable-row" data-href="?id=<?= $warehouse->getId();?>">
                            <td><?= $warehouse->getId();?></td>
                            <td><?= $warehouse->getName();?></td>
                            <td><?= $warehouse->getStreetNumber()." ".$warehouse->getStreetName()." ".$warehouse->getZipCode()." ".$warehouse->getCity();?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>

<script type="text/javascript">
    function updateStock(idItem, idWarehouse){
        var quantity = $("#article" + idItem).val();
        var parameters = {
            quantity: quantity,
            idFood: idItem,
            idWarehouse: idWarehouse
        };

        $.ajax({
            url : '/api/stock',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(parameters),
        }).always(function(data){
            if (data.status === 400){
                Swal.fire({
                    title: "Erreur",
                    icon: "error",
                    text: "Veuillez changer la valeur avant de modifier"
                });
            }else{
                if (data.status === "success"){
                    Swal.fire({
                        title: "Succès",
                        icon: "success",
                        text: "Le stock a bien été modifié"
                    });
                }else{
                    Swal.fire({
                        title: "Erreur",
                        icon: "error",
                        text: "Erreur lors du changement de stock"
                    });
                }
            }
            console.log(data);
        });
    }
</script>