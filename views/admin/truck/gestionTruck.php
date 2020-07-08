<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
$tRepo = new FoodTruckRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion des camions");?>
        <span class="float-right">
        <a class="mb-2 btn btn-primary" href="addTruck">
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
            <th><?= translate("Franchisé");?></th>
            <th><?= translate("Nom");?></th>
            <th><?= translate("Ville");?></th>
            <th><?= translate("Code postale");?></th>
            <th><?= translate("N°");?></th>
            <th><?= translate("Rue");?></th>
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
                    <td class="inputDate<?= $truck->getId();?>"><?= $truck->getDateCheck() != null ? date('d/m/Y',strtotime($truck->getDateCheck())):translate("Pas de dernier checkup");?></td>
                    <td class="inputMileage<?= $truck->getId();?>"><?= $truck->getMileage();?></td>
                    <td>
                        <?php
                        $franchisee = $tRepo->getUser($truck);
                        if ($franchisee != null){
                            $fullName = strtoupper($franchisee->getLastname()) . " " . $franchisee->getFirstname();
                            ?>
                            <a href="../manageFranchisees?id=<?= $franchisee->getId()?>" target="blank"><?= $fullName;?></a>
                            <?php
                        }else{
                            echo translate("Aucun");
                        }
                        ?>
                    </td>
                    <td class="inputName<?= $truck->getId();?>"><?= $truck->getName();?></td>
                    <td class="inputCity<?= $truck->getId();?>"><?= $truck->getCity();?></td>
                    <td class="inputZipcode<?= $truck->getId();?>"><?= $truck->getZipcode();?></td>
                    <td class="inputStreetNumber<?= $truck->getId();?>"><?= $truck->getStreetNumber();?></td>
                    <td class="inputStreetName<?= $truck->getId();?>"><?= $truck->getStreetName();?></td>
                    <td id="actions<?= $truck->getId();?>">
                        <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(<?= $truck->getId();?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(<?= $truck->getId();?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" href="manageFranchisee?id=<?= $truck->getId();?>">
                            <i class="fas fa-user"></i>
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
    function deleteTruck(truck){
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
        $('[data-toggle="tooltip"]').tooltip('dispose');
        var date = $('.inputDate' + truck);
        var mileage = $('.inputMileage' + truck);
        var name = $('.inputName' + truck);
        var city = $('.inputCity' + truck);
        var zipcode = $('.inputZipcode' + truck);
        var streetNumber = $('.inputStreetNumber' + truck);
        var streetName = $('.inputStreetName' + truck);

        var dateVal = date.html();
        var mileageVal = mileage.html();
        var nameVal = name.html();
        var cityVal = city.html();
        var zipcodeVal = zipcode.html();
        var streetNumerVal = streetNumber.html();
        var streetNameVal = streetName.html();

        date.html("<input type='date' class='form-control' value='" + formatDate(dateVal,"Y-m-d") + "'>");
        mileage.html("<input type='number' class='form-control' value='" + mileageVal + "'>");
        name.html("<input type='text' class='form-control' value='" + nameVal + "'>");
        city.html("<input type='text' class='form-control' value='" + cityVal + "'>");
        zipcode.html("<input type='text' class='form-control' value='" + zipcodeVal + "'>");
        streetNumber.html("<input type='number' class='form-control' value='" + streetNumerVal + "'>");
        streetName.html("<input type='text' class='form-control' value='" + streetNameVal + "'>");

        var actionsRow = $('#actions' + truck);
        var btn =   '<button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Valider");?>" onclick="submitEdit(' + truck + ')">' +
                        '<i class="fa fa-check"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-danger" data-toggle="tooltip" title="<?= translate("Annuler");?>" onclick="cancelEdit(\'' + dateVal + '\',\'' + mileageVal +  '\',\'' + nameVal + '\',\'' + cityVal + '\',\'' + zipcodeVal + '\',\'' + streetNumerVal + '\',\'' + streetNameVal + '\',' + truck +')">' +
                        '<i class="fas fa-times"></i>' +
                    '</button>';
        actionsRow.html(btn);
        $('[data-toggle="tooltip"]').tooltip('enable');
    }

    function submitEdit(truck){
        var date = $('.inputDate' + truck);
        var mileage = $('.inputMileage' + truck);
        var name = $('.inputName' + truck);
        var city = $('.inputCity' + truck);
        var zipcode = $('.inputZipcode' + truck);
        var streetNumber = $('.inputStreetNumber' + truck);
        var streetName = $('.inputStreetName' + truck);

        var Truck = {id: truck,date: date.find('input').val(), mileage: mileage.find('input').val(), name: name.find('input').val(), city: city.find('input').val(), zipcode: zipcode.find('input').val(), street_number: streetNumber.find('input').val(), street_name: streetName.find('input').val()}

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
                name.html(Truck.name);
                city.html(Truck.city);
                zipcode.html(Truck.zipcode);
                streetNumber.html(Truck.street_number);
                streetName.html(Truck.street_name);

                var actionsRow = $('#actions' + truck);
                actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(' + truck + ')">' +
                    '<i class="fas fa-trash"></i>' +
                    '</button>\n' +
                    '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
                    '<i class="fas fa-edit"></i>' +
                    '</button>\n' +
                    '<a class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" href="manageFranchisee?id=' + truck + '">' +
                    '<i class="fas fa-user"></i>' +
                    '</a>\n'
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

    function cancelEdit(date, mileage, name, city, zipcode, streetNumber, streetName, truck){
        $('[data-toggle="tooltip"]').tooltip('dispose');
        var dateTd = $('.inputDate' + truck);
        var mileageTd = $('.inputMileage' + truck);
        var nameTd = $('.inputName' + truck);
        var cityTd = $('.inputCity' + truck);
        var zipcodeTd = $('.inputZipcode' + truck);
        var streetNumberTd = $('.inputStreetNumber' + truck);
        var streetNameTd = $('.inputStreetName' + truck);

        dateTd.html(date);
        mileageTd.html(mileage);
        nameTd.html(name);
        cityTd.html(city);
        zipcodeTd.html(zipcode);
        streetNumberTd.html(streetNumber);
        streetNameTd.html(streetName);

        var actionsRow = $('#actions' + truck);
        actionsRow.html('<button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteTruck(' + truck + ')">' +
            '<i class="fas fa-trash"></i>' +
            '</button>\n' +
            '<button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editTruck(' + truck + ')">' +
            '<i class="fas fa-edit"></i>' +
            '</button>\n' +
            '<a class="btn btn-primary" title="<?= translate("Franchisé");?>" data-toggle="tooltip" href="manageFranchisee?id=' + truck + '">' +
            '<i class="fas fa-user"></i>' +
            '</a>\n'
        );

        $('[data-toggle="tooltip"]').tooltip();
    }
</script>