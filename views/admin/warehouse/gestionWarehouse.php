<?php
require_once __DIR__ . "/../../../repositories/WarehouseRepository.php";
$wRepo = new WarehouseRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion des entrepôts");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion des entrepôts");?>
        <span class="float-right">
        <a class="mb-2 btn btn-primary" href="addwarehouse">
            <i class="fa fa-plus"></i> <?= translate("Ajouter un entrepôt");?>
        </a>
    </span>
    </h1>
    <p class="lead">

    </p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= translate("ID");?></th>
            <th><?= translate("Nom");?></th>
            <th><?= translate("N°");?></th>
            <th><?= translate("Rue");?></th>
            <th><?= translate("Code Postale");?></th>
            <th><?= translate("Ville");?></th>
            <th><?= translate("Actions");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $warehouses = $wRepo->getAll();

        if ($warehouses != null){
            foreach ($warehouses as $warehouse){
                ?>
                <tr id="row<?= $warehouse->getId();?>">
                    <td><?= $warehouse->getId();?></td>
                    <td class="inputName<?= $warehouse->getId();?>"><?= $warehouse->getName();?></td>
                    <td><?= $warehouse->getStreetNumber();?></td>
                    <td><?= $warehouse->getStreetName();?></td>
                    <td><?= $warehouse->getZipcode();?></td>
                    <td><?= $warehouse->getCity();?></td>
                    <td id="actions<?= $warehouse->getId();?>">
                        <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteWarehouse(<?= $warehouse->getId();?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editWarehouse(<?= $warehouse->getId();?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a class="btn btn-primary" title="<?= translate("Stock");?>" data-toggle="tooltip" href="#">
                            <i class="fas fa-tasks"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function deleteWarehouse(warehouse){
        Swal.fire({
            title: '<?= translate("Êtes-vous sûr ?");?>',
            text: '<?= translate("Vous ne pourrez pas revenir en arrière");?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#0205a1',
            confirmButtonText: "<?= translate('Supprimer');?>",
            cancelButtonText: "<?= translate('Annuler');?>",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : '/api/warehouse',
                    type: 'DELETE',
                    data: 'id=' + warehouse
                }).done(function(data){
                    Swal.fire({
                        title: "<?= translate('Supprimé');?>",
                        text:"<?= translate("L'entrepôt a bien été supprimé");?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location.reload(true);
                    })
                });

            }
        })
    }

    function editWarehouse(warehouse) {
        $('[data-toggle="tooltip"]').tooltip('dispose');
        var name = $('.inputName' + warehouse);

        var nameVal = name.html();
        name.html("<input type='text' class='form-control' value='" + nameVal + "'>");

        var actionsRow = $('#actions' + warehouse);
        var btn =   '<button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Valider");?>" onclick="submitEdit(' + warehouse + ')">' +
                        '<i class="fa fa-check"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-danger" data-toggle="tooltip" title="<?= translate("Annuler");?>" onclick="cancelEdit(\'' + nameVal+'\',\''+warehouse+'\')">' +
                        '<i class="fas fa-times"></i>' +
                    '</button>';
        actionsRow.html(btn);
        $('[data-toggle="tooltip"]').tooltip('enable');
    }

    function submitEdit(warehouse){
        var name = $('.inputName' + warehouse);

        var Warehouse = {id: warehouse, name: name.find('input').val()}

        $.ajax({
            url : '/api/warehouse',
            type: 'PUT',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(Warehouse)
        }).done(function(data){
            if(data.status === "success"){
                name.html(Warehouse.name);

                var actionsRow = $('#actions' + warehouse);
                actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteWarehouse(' + warehouse + ')">' +
                    '<i class="fas fa-trash"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editWarehouse(' + warehouse + ')">' +
                    '<i class="fas fa-edit"></i>' +
                    '</button>\n' +
                    '<a class="btn btn-primary" title="<?= translate("Stock");?>" data-toggle="tooltip" href="#">' +
                    '<i class="fas fa-tasks"></i>' +
                    '</a>\n'
                );
                Swal.fire({
                    title: '<?= translate("Succès");?>',
                    text: "<?= translate("L'entrepôt a bien été mis à jour");?>",
                    icon: "success"
                });
                $('.tooltip').remove();
                $('[data-toggle="tooltip"]').tooltip();

            }else{
                Swal.fire({
                    title: '<?= translate("Erreur");?>',
                    text: "<?= translate("Erreur lors de la mise à jour de l'entrepôt, les valeurs ne sont pas valables ou n\'ont pas changé");?>",
                    icon: "error"
                });
            }
        });
    }

    function cancelEdit(name, warehouse){
        $('[data-toggle="tooltip"]').tooltip('dispose');
        var nameTd = $('.inputName' + warehouse);

        nameTd.html(name);

        var actionsRow = $('#actions' + warehouse);
        actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteWarehouse(' + warehouse + ')">' +
            '<i class="fas fa-trash"></i>' +
            '</button>\n' +
            '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editWarehouse(' + warehouse + ')">' +
            '<i class="fas fa-edit"></i>' +
            '</button>\n' +
            '<a class="btn btn-primary" title="<?= translate("Stock");?>" data-toggle="tooltip" href="#">' +
            '<i class="fas fa-tasks"></i>' +
            '</a>\n'
        );

        $('[data-toggle="tooltip"]').tooltip();
    }
</script>