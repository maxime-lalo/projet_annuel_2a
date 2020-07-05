<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";
$tRepository = new FoodTruckRepository();
$uRepository = new UserRepository();
$truck = $tRepository->getFromUserId($user->getId());
if ($truck != null){
    $isOnBreakdown = $tRepository->isOnBreakdown($truck);
}

if (isset($_GET['setBreakdown'])){
    if (!$isOnBreakdown){
        $tRepository->setBreakdown($tRepository->getOneById($_GET['setBreakdown']));
    }
}elseif(isset($_GET['cancelBreakdown'])){
    $tRepository->cancelBreakdown($tRepository->getOneById($_GET['cancelBreakdown']));
}
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Mon camion");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Mon camion");?>
        <?php
        if ($truck != null){
            ?>
            <span class="float-right">
                <a href="truckHistory" class="btn btn-primary mb-2">
                    <i class="fa fa-history"></i> <?= translate("Historique des pannes");?>
                </a>
            </span>
            <?php
        }
        ?>
    </h1>
    <?php

    if ($truck != null){
        if ($isOnBreakdown){
            ?>
            <p class="lead" style="color:red"><?= translate("Votre camion est actuellement marqué comme en panne");?></p>
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
        <table class="table table-bordered">
            <thead>
            <tr>
                <th><?= translate("Ville");?></th>
                <th><?= translate("Code postale");?></th>
                <th><?= translate("N°");?></th>
                <th><?= translate("Rue");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="inputCity"><?= $truck->getCity();?></td>
                <td id="inputZipCode"><?= $truck->getZipcode();?></td>
                <td id="inputStreetNumber"><?= $truck->getStreetNumber();?></td>
                <td id="inputStreetName"><?= $truck->getStreetName();?></td>
                <td id="actions<?= $truck->getId();?>">
                    <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(<?= $truck->getId();?>)">
                        <i class="fas fa-edit"></i>
                    </button>    
                </td>
            </tr>
            </tbody>
        </table>
        <?php
        if (!$isOnBreakdown){
            ?>
            <a class="btn btn-danger" href="?setBreakdown=<?= $truck->getId();?>"><?= translate("Déclarer une panne");?></a>
            <?php
        }
    }else{
        ?>
        <p class="lead"><?= translate("Aucun camion ne vous a encore été attribué");?></p>
        <?php
    }
    ?>
</div>

<script>
    function editTruck(truck) {
        var city = $('#inputCity');
        var zipcode = $('#inputZipCode');
        var streetNumber = $('#inputStreetNumber');
        var streetName = $('#inputStreetName');

        var cityVal = city.html();
        var zipcodeVal = zipcode.html();
        var streetNumerVal = streetNumber.html();
        var streetNameVal = streetName.html();
        city.html("<input type='text' class='form-control' value='" + cityVal + "'>");
        zipcode.html("<input type='text' class='form-control' value='" + zipcodeVal + "'>");
        streetNumber.html("<input type='number' class='form-control' value='" + streetNumerVal + "'>");
        streetName.html("<input type='text' class='form-control' value='" + streetNameVal + "'>");

        var actionsRow = $('#actions' + truck);
        var btn =   '<button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Valider");?>" onclick="submitEdit(' + truck + ')">' +
                        '<i class="fa fa-check"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-danger" data-toggle="tooltip" title="<?= translate("Annuler");?>" onclick="cancelEdit(\'' + cityVal + '\',\'' + zipcodeVal + '\',\'' + streetNumerVal + '\',\'' + streetNameVal + '\',' + truck +')">' +
                        '<i class="fas fa-times"></i>' +
                    '</button>';
        actionsRow.html(btn);
    }

    function submitEdit(truck){
        var city = $('#inputCity');
        var zipcode = $('#inputZipCode');
        var streetNumber = $('#inputStreetNumber');
        var streetName = $('#inputStreetName');

        var Truck = {id: truck, city: city.find('input').val(), zipcode: zipcode.find('input').val(), street_number: streetNumber.find('input').val(), street_name: streetName.find('input').val()}

        $.ajax({
            url : '/api/truck',
            type: 'PUT',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(Truck)
        }).done(function(data){
            if(data.status === "success"){
                city.html(Truck.city);
                zipcode.html(Truck.zipcode);
                streetNumber.html(Truck.street_number);
                streetName.html(Truck.street_name);

                var actionsRow = $('#actions' + truck);
                actionsRow.html('<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
                    '<i class="fas fa-edit"></i>' +
                    '</button>\n'
                );
                Swal.fire({
                    title: '<?= translate("Succès");?>',
                    text: '<?= translate("Le camion a bien été mis à jour");?>',
                    icon: "success"
                });
                $('.tooltip').remove();
                $('[data-toggle="tooltip"]').tooltip();

            }else{
                Swal.fire({
                    title: '<?= translate("Erreur");?>',
                    text: '<?= translate("Erreur lors de la mise à jour du camion, les valeurs ne sont pas valables ou n\'ont pas changé");?>',
                    icon: "error"
                });
            }
        });
    }

    function cancelEdit(city, zipcode, streetNumber, streetName, truck){
        var cityTd = $('#inputCity');
        var zipcodeTd = $('#inputZipCode');
        var streetNumberTd = $('#inputStreetNumber');
        var streetNameTd = $('#inputStreetName');

        cityTd.html(city);
        zipcodeTd.html(zipcode);
        streetNumberTd.html(streetNumber);
        streetNameTd.html(streetName);

        var actionsRow = $('#actions' + truck);
        actionsRow.html('<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
            '<i class="fas fa-edit"></i>' +
            '</button>\n' 
        );

        $('[data-toggle="tooltip"]').tooltip();
    }
</script>