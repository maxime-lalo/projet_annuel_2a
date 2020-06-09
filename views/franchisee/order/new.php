<?php
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";
$rRepo = new RecipeRepository();
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Nouvelle commande");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Nouvelle commande");?>
    </h1>
    <p class="lead">
        <?= translate("Pour vous simplifier la vie, il suffit de commander une recette pour commander tous les produits qui y sont associés !");?>
    </p>
    <table class="table">
        <thead>
            <tr>
                <th><?= translate("Nom de la recette");?></th>
                <th><?= translate("Nombre d'ingrédients");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recipes = $rRepo->getAll();
            if ($recipes){
                foreach ($recipes as $recipe){
                    ?>
                    <tr>
                        <td><?= $recipe->getName();?></td>
                        <td><?= count($recipe->getIngredients());?></td>
                        <td>
                            <button class="btn btn-primary" data-toggle="tooltip" title="<?= translate("Voir les ingrédients");?>" onclick="showRecipe(<?= $recipe->getId();?>)">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Ajouter au panier");?>">
                                <i class="fa fa-box"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr>
                    <td colspan="3"><?= translate("Pas de recette disponible");?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function showRecipe(idRecipe){
        $.ajax({
            url : '/api/recipe',
            type: 'GET',
            data: 'id=' + idRecipe
        }).done(function(data){
            var strIngredients = "";
            for (var i = 0; i < data.Recipe.ingredients.length;i++){
                strIngredients += "<tr>";
                strIngredients += "<td>" + data.Recipe.ingredients[i].name;
                strIngredients += "<td>" + data.Recipe.ingredients[i].type;
                strIngredients += "<td>" + data.Recipe.ingredients[i].quantity;
                strIngredients += "</tr>";
            }
            Swal.fire({
                title: 'Recette pour : ' + data.Recipe.name,
                icon: 'info',
                html:
                    '<table class="table table-bordered text-center"> ' +
                        '<thead>' +
                            '<tr>' +
                                '<th>Nom</th>' +
                                '<th>Type</th>' +
                                '<th>Quantité</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            strIngredients +
                        '</tbody>' +
                    '</table>',
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Génial!',
            })
        });
    }
</script>