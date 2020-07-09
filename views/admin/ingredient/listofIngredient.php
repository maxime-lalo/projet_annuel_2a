<?php
require_once __DIR__ . "/../../../repositories/FoodRepository.php";
$foodRepo = new FoodRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion des Ingredients");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion des Ingredients");?>

    </h1>
    <p class="lead">
        <a  href="/<?= LANG; ?>/admin/ingredient/addIngredient" style="text-decoration: none;color: #1b1e21;">
            <button class="btn btn-warning btn-lg btn-block" title="<?= translate("Ajouter Ingredient");?>"> Ajouter Ingredient
                <i class="fas fa-flask"></i>
            </button>
        </a>
    </p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th><?= translate("ID");?></th>
        <th><?= translate("Nom");?></th>
        <th><?= translate("Poid");?></th>
        <th><?= translate("Type");?></th>
        <th><?= translate("Actions");?></th>
    </tr>
    </thead>
    <tbody>
<?php
$foods = $foodRepo->getAll();
if($foods != null) {
foreach ($foods as $food){
?>
<tr id="row<?= $food->getId();?>">
    <td><?= $food->getId();?></td>
    <td><?= $food->getName();?></td>
    <td><?= $food->getWeight() . $food->getUnity();?></td>
    <td><?= $food->getType();?></td>
    <td id="actions<?= $food->getId();?>">
        <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteIngredient(<?= $food->getId();?>)">
            <i class="fas fa-trash"></i>
        </button>
        <a href="/<?= LANG; ?>/admin/ingredient/addIngredient?id=<?=$food->getId();?>">
        <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip">
            <i class="fas fa-edit"></i>
        </button>
        </a>
    </td>
</tr>
<?php
}
}
else {
    echo "pas d'ingredients en base";
}?>
</tbody>
</table>



<script type="text/javascript">
    function deleteIngredient(ingredient){
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
                    url : '/api/stock',
                    type: 'DELETE',
                    data: 'id=' + ingredient
                }).done(function(data){
                    Swal.fire({
                        title: "<?= translate('Supprimé');?>",
                        text:"<?= translate('L\'ingrédient a bien été supprimé');?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location.reload(true);
                    })
                });

            }
        })
    }
    </script>
