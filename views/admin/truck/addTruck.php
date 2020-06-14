<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
$tRepo = new FoodTruckRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Ajouter un camion");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Ajouter un camion");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="gestionTruck">
                <i class="fa fa-edit"></i> <?= translate("Gestion des camions");?>
            </a>
        </span>
    </h1>

    <form method="POST" id="formAdd">
        <div class="form-group">
            <label for="brand"><?= translate("Marque");?></label>
            <input type="text" class="form-control" name="brand" id="brand" required>
        </div>
        <div class="form-group">
            <label for="model"><?= translate("Modèle");?></label>
            <input type="text" class="form-control" name="model" id="model" required>
        </div>
        <div class="form-group">
            <label for="mileage"><?= translate("Kilométrage");?></label>
            <input type="number" class="form-control" name="mileage" id="mileage" required>
        </div>
        <div class="form-group">
            <label for="date_last_check"><?= translate("Date du dernier checkup");?></label>
            <input type="date" class="form-control" name="date_last_check" id="date_last_check" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitForm()">
            <i class="fa fa-plus"></i> <?= translate('Ajouter');?>
        </button>
    </form>
</div>
<script type="text/javascript">
    function submitForm(){
        var form = {
            brand: $('#brand').val(),
            model: $('#model').val(),
            mileage: $('#mileage').val(),
            date_last_check: $('#date_last_check').val()
        }

        var error = false;
        if (form.brand === ""){
            error = true;
        }

        if (form.mileage === ""){
            error = true;
        }

        if (form.date_last_check === ""){
            form.date_last_check = null;
        }

        if (form.model === ""){
            error = true;
        }

        if (error === true){
            swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez remplir tous les champs'
            })
        }else{
            $.ajax({
                url : '/api/truck',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(form),
            }).done(function(data){
                if (data.status !== "error"){
                    Swal.fire({
                        title: "<?= translate('Succès');?>",
                        text:"<?= translate('Le camion a bien été ajouté');?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location = "gestionTruck"
                    })
                }else{
                    Swal.fire({
                        title: '<?= translate("Erreur");?>',
                        text: '<?= translate("Erreur lors de la création du camion");?>',
                        icon: "error"
                    });
                }
            });
        }
    }
</script>