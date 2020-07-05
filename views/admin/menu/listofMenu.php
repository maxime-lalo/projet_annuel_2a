<?php
require_once __DIR__ . "/../../../repositories/MenuRepository.php";
$menuRepo = new MenuRepository();
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion des menus");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion des menus");?>

    </h1>
    <p class="lead">

    </p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th><?= translate("ID");?></th>
        <th><?= translate("Nom");?></th>
        <th><?= translate("Contenu");?></th>
        <th><?= translate("Prix");?></th>
        <th><?= translate("Actions");?></th>
    </tr>
    </thead>
    <tbody>
<?php
$menus = $menuRepo->getAll();
if($menus != null ) {
foreach ($menus as $menu){
?>
<tr id="row<?= $menu->getId();?>">
    <td><?= $menu->getId();?></td>
    <td><?= $menu->getName();?></td>
    <td>
   <?php
   foreach ($menu->getRecipes() as $recipe){
        ?>
   <?= $recipe->getName() . "  - ";?>
    <?php } ?>
   <?php
   $ingredients = $menu->getIngredients();
   $last_key = end($ingredients);
   foreach ($ingredients as $ingredient){
       if($ingredient != $last_key) {
       ?>
       <?= $ingredient->getName() . "  - ";?>
   <?php }
       else{ ?>
        <?= $ingredient->getName();?>
           <?php
   }
   }?>
    </td>
    <td><?= $menu->getPrice() . " €";?></td>
    <td id="actions<?= $menu->getId();?>">
        <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteMenu(<?= $menu->getId();?>)">
            <i class="fas fa-trash"></i>
        </button>
        <a href="/<?= LANG; ?>/admin/menu/addMenu?id=<?=$menu->getId();?>">
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
    echo "pas de menus en base";
}?>
</tbody>
</table>


<a  href="/<?= LANG; ?>/admin/menu/addMenu" style="text-decoration: none;color: #1b1e21;">
    <button class="btn btn-warning btn-lg btn-block" title="<?= translate("Fabriquer Menus");?>"> Fabriquer Menus
        <i class="fas fa-flask"></i>
    </button>
</a>
<script type="text/javascript">
    function deleteMenu(menu){
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
                    url : '/api/menu',
                    type: 'DELETE',
                    data: 'id=' + menu
                }).done(function(data){
                    Swal.fire({
                        title: "<?= translate('Supprimé');?>",
                        text:"<?= translate('Le menu a bien été supprimé');?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location.reload(true);
                    })
                });

            }
        })
    }
    </script>
