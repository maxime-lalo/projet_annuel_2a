<?php
require_once __DIR__ . "/../../../repositories/FoodRepository.php";


$foodRepo = new FoodRepository();
$name = "";
$type = "";
$weight = "";
if(isset($_GET['id'])){
    $id_food = $_GET['id'];
    $food = $foodRepo->getOneById($id_food);
if($food != null) {
    $name = $food->getName();
    $type = $food->getType();
    $weight = $food->getWeight();
}
}

?>
<?php if(isset($food)) {  ?>
<title><?= translate("Espace Admin");?> - <?= translate("Modifier un ingredient");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Modifier un ingredient");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="listofIngredient">
                <i class="fa fa-edit"></i> <?= translate("Gestion des ingredients");?>
            </a>
        </span>
    </h1>
<?php } else { ?>
    <title><?= translate("Espace Admin");?> - <?= translate("Ajouter un ingredient");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Ajouter un ingredient");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="listofIngredient">
                <i class="fa fa-edit"></i> <?= translate("Gestion des ingredients");?>
            </a>
        </span>
    </h1>
<?php } ?>

    <form method="POST" id="formAdd">
        <div class="form-group">
            <label for="name"><?= translate("Nom de l'ingredient");?></label>
            <input type="text" class="form-control" name="name" id="name" value="<?=$name;?>" required>
<?php if(isset($food)) {  ?>  <input type="text" class="form-control" style="visibility: hidden;" name="ingredient_exist" id="ingredient_exist" value="<?=$id_food;?>" required> <?php } ?>
        </div>

        <div class="form-group">
            <label for="name"><?= translate("Poid");?></label>
            <input type="number" step="1" min="1" class="form-control"  name="weight" id="weight" value="<?=$weight;?>" required>
        </div>

        <div class="form-group">
            <label for="name"><?= translate("Unité");?></label>
            <select name="unity" id="unity" class="form-control"  >
                <option selected="selected">
                    <?php if(isset($food)) {  ?>         <?= $food->getUnity();  }?>
                </option>
                <option>g</option>
                <option>cl</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name"><?= translate("Type d'ingrédient");?></label>
            <input type="text" class="form-control" name="type" id="type" value="<?=$type;?>" required>

        </div>

        <?php if(isset($food)) { ?>
            <button type="button" class="btn btn-primary" onclick="updateForm()">
            <i class="fas fa-flask"></i> <?= translate('Modifier');?>
        </button>   <?php } else { ?>
            <button type="button" class="btn btn-primary" onclick="submitForm()">
            <i class="fas fa-flask"></i> <?= translate('Ajouter');?>
        </button>
        <?php } ?>
    </form>
</div>
<script type="text/javascript">
    function submitForm(){


        var error = false;

        if (error === true){
            swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez remplir tous les champs'
            })
        }else{
            $.ajax({
                url : '/api/stock',
                type: 'POST',
                data: $('#formAdd').serialize()
            }).done(function(data){
                if (data.status !== "error"){
                    Swal.fire({
                        title: "<?= translate('Succès');?>",
                        text: "<?= translate("L'ingredient a bien été ajouté");?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location = "listofIngredient"
                    })
                }else{
                    Swal.fire({
                        title: '<?= translate("Erreur");?>',
                        text: "<?= translate("Erreur lors de la création de l'ingredient");?>",
                        icon: "error"
                    });
                }
            });
        }
    }

    function updateForm(){
        var error = false;

if (error === true){
    swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: 'Veuillez remplir tous les champs'
    })
}else{
    $.ajax({
        url : '/api/stock',
        type: 'POST',
        data: $('#formAdd').serialize()
    }).done(function(data){
        if (data.status !== "error"){
            Swal.fire({
                title: "<?= translate('Succès');?>",
                text: "<?= translate("L'ingredient a bien été ajouté");?>",
                icon: 'success'
            }).then((result) => {
                document.location = "listofIngredient"
            })
        }else{
            Swal.fire({
                title: '<?= translate("Erreur");?>',
                text: "<?= translate("Erreur lors de la création de l'ingredient");?>",
                icon: "error"
            });
        }
    });
}


    }
</script>



