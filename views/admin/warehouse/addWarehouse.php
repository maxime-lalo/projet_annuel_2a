<?php
require_once __DIR__ . "/../../../repositories/WarehouseRepository.php";
$tRepo = new WarehouseRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Ajouter un entrepôt");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Ajouter un entrepôt");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="gestionWarehouse">
                <i class="fa fa-edit"></i> <?= translate("Gestion des entrepôts");?>
            </a>
        </span>
    </h1>

    <form method="POST" id="formAdd">
        <div class="form-group">
            <label for="name"><?= translate("Nom");?></label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="street_number"><?= translate("N°");?></label>
            <input type="number" class="form-control" name="street_number" id="street_number" required>
        </div>
        <div class="form-group">
            <label for="street_name"><?= translate("Rue");?></label>
            <input type="text" class="form-control" name="street_name" id="street_name" required>
        </div>
        <div class="form-group">
            <label for="zipcode"><?= translate("Code postale");?></label>
            <input type="text" class="form-control" name="zipcode" id="zipcode" required>
        </div>
        <div class="form-group">
            <label for="city"><?= translate("Ville");?></label>
            <input type="text" class="form-control" name="city" id="city" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitForm()">
            <i class="fa fa-plus"></i> <?= translate('Ajouter');?>
        </button>
    </form>
</div>
<script type="text/javascript">
    function submitForm(){
        var form = {
            name: $('#name').val(),
            street_number: $('#street_number').val(),
            street_name: $('#street_name').val(),
            zipcode: $('#zipcode').val(),
            city: $('#city').val()
        }

        var error = false;
        for (const [key, value] of Object.entries(form)) {
            if(value === "") error = true;
        }
        if (error === true){
            swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez remplir tous les champs'
            })
        }else{
            $.ajax({
                url : '/api/warehouse',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(form),
            }).done(function(data){
                if (data.status !== "error"){
                    Swal.fire({
                        title: "<?= translate('Succès');?>",
                        text: "<?= translate("L'entrepôt a bien été ajouté");?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location = "gestionWarehouse"
                    })
                }else{
                    Swal.fire({
                        title: '<?= translate("Erreur");?>',
                        text: "<?= translate("Erreur lors de la création de L'entrepôt");?>",
                        icon: "error"
                    });
                }
            });
        }
    }
</script>