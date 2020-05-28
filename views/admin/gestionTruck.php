<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
$tRepo = new FoodTruckRepository();
if (isset($_POST['action']) && isset($_POST['truck'])){

}else{
    ?>
    <title><?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?></title>
    <div class="container">
        <h1 id="page-title">
            <?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?>
            <span class="float-right">
            <button type="button" class="mb-2 btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> <?= translate("Ajouter un camion");?>
            </button>
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
                <th><?= translate("Franchisé");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $trucks = $tRepo->getAll();

            if ($trucks != null){
                foreach ($trucks as $truck){
                    ?>
                    <tr id="row<?= $truck->getId();?>">
                        <td><?= $truck->getId();?></td>
                        <td><?= $truck->getBrand();?></td>
                        <td><?= $truck->getModel();?></td>
                        <td><?= date('d/m/Y',strtotime($truck->getDateRegister()));?></td>
                        <td class="inputDate<?= $truck->getId();?>"><?= date('d/m/Y',strtotime($truck->getDateCheck()));?></td>
                        <td class="inputMileage<?= $truck->getId();?>"><?= $truck->getMileage();?></td>
                        <td>
                            <?php
                            $franchisee = $tRepo->getUser($truck);
                            if ($franchisee != null){
                                $fullName = strtoupper($franchisee->getLastname()) . " " . $franchisee->getFirstname();
                                ?>
                                <a href="#" target="blank"><?= $fullName;?></a>
                                <?php
                            }else{
                                echo translate("Aucun");
                            }
                            ?>
                        </td>
                        <td id="actions<?= $truck->getId();?>">
                            <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(<?= $truck->getId();?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(<?= $truck->getId();?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" onclick="editTruck(<?= $truck->getId();?>)">
                                <i class="fas fa-user"></i>
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
    <?php
}
?>


<script type="text/javascript">
    function deleteTruck(truck){
        Swal.fire({
            title: '<?= translate("Êtes-vous sûr ?");?>',
            text: '<?= translate("Vous ne pourrez pas revenir en arrière");?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0205a1',
            cancelButtonColor: '#d33',
            confirmButtonText: "<?= translate('Supprimer');?>",
            cancelButtonText: "<?= translate('Annuler');?>"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : '/api/truck',
                    type: 'DELETE',
                    data: 'id=' + truck
                }).done(function(data){
                    Swal.fire({
                        title: "<?= translate('Supprimé');?>",
                        text:"<?= translate('Le camion a bien été supprimé');?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location.reload(true);
                    })
                });

            }
        })
    }

    function editTruck(truck) {
        var date = $('.inputDate' + truck);
        var mileage = $('.inputMileage' + truck);

        var dateVal = date.html();
        var mileageVal = mileage.html();
        date.html("<input type='date' class='form-control' value='" + formatDate(dateVal,"Y-m-d") + "'>");
        mileage.html("<input type='number' class='form-control' value='" + mileageVal + "'>");

        var actionsRow = $('#actions' + truck);
        var btn =   "<button class='btn btn-success' data-toggle='tooltip' title='Valider' onclick='submitEdit(" + truck + ")'>" +
                        "<i class='fa fa-check'></i>" +
                    "</button>\n" +
                    "<button class='btn btn-danger' data-toggle='tooltip' title'Annuler' onclick='cancelEdit(\"" + dateVal + "\",\"" + mileageVal + "\",\"" + truck + "\")'>" +
                        "<i class='fas fa-times'></i>" +
                    "</button>"
        ;
        actionsRow.html(btn);
    }

    function submitEdit(truck){
        var date = $('.inputDate' + truck);
        var mileage = $('.inputMileage' + truck);

        var Truck = {id: truck,date: date.find('input').val(), mileage: mileage.find('input').val()}

        $.ajax({
            url : '/api/truck',
            type: 'PUT',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(Truck)
        }).done(function(data){
            if(data.status === "success"){
                date.html(formatDate(Truck.date,'d/m/Y'));
                mileage.html(Truck.mileage);

                var actionsRow = $('#actions' + truck);
                actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(' + truck + ')">' +
                    '<i class="fas fa-trash"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
                    '<i class="fas fa-edit"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
                    '<i class="fas fa-user"></i>' +
                    '</button>\n'
                );

                Swal.fire({
                    title: '<?= translate("Succès");?>',
                    text: '<?= translate("Le camion a bien été mis à jour");?>',
                    icon: "success"
                });
            }else{
                Swal.fire({
                    title: '<?= translate("Erreur");?>',
                    text: '<?= translate("Erreur lors de la mise à jour du camion, les valeurs ne sont pas valables ou n\'ont pas changé");?>',
                    icon: "error"
                });
            }
        });
    }

    function cancelEdit(date,mileage,truck){
        var dateTd = $('.inputDate' + truck);
        var mileageTd = $('.inputMileage' + truck);

        dateTd.html(date);
        mileageTd.html(mileage);

        var actionsRow = $('#actions' + truck);
        actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(' + truck + ')">' +
            '<i class="fas fa-trash"></i>' +
            '</button>\n' +
            '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
            '<i class="fas fa-edit"></i>' +
            '</button>\n' +
            '<button class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
            '<i class="fas fa-user"></i>' +
            '</button>\n'
        );
    }
</script>