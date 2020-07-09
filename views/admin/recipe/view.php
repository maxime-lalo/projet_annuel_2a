<?php
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";
$rRepo = new RecipeRepository();
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 id="page-title"><?= translate("Gestion des recettes");?></h1>
            <table class="table table-bordered table-striped">
                <thead>
                    <th><?= translate("Id");?></th>
                    <th><?= translate("Nom");?></th>
                    <th><?= translate("Nombre d'ingrédients");?></th>
                    <th><?= translate("Actions");?></th>
                </thead>
                <tbody>
                    <?php
                    $allRecipes = $rRepo->getAll();
                    if ($allRecipes){
                        /* @var $recipe Recipe */
                        foreach($allRecipes as $recipe){
                            ?>
                            <tr>
                                <td><?= $recipe->getId();?></td>
                                <td><?= $recipe->getName();?></td>
                                <td><?= count($recipe->getIngredients());?></td>
                                <td>
                                    <a href="edit?id=<?= $recipe->getId();?>" class="btn btn-primary" title="Modifier" data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-primary" data-toggle="tooltip" title="Voir la recette" onclick="showRecipe(<?= $recipe->getId();?>)">
                                        <i class="fa fa-eye"></i>
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
    </div>
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
                strIngredients +=
                    "<tr>" +
                    "<td>" + data.Recipe.ingredients[i].name + "</td>" +
                    "<td>" + data.Recipe.ingredients[i].type + "</td>" +
                    "<td>" + data.Recipe.ingredients[i].quantity + data.Recipe.ingredients[i].unity +"</td>" +
                    "</tr>";
            }
            Swal.fire({
                title: 'Recette pour : ' + data.Recipe.name,
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
