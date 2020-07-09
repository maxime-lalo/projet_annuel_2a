<?php
require_once  __DIR__ . "/../../../repositories/RecipeRepository.php";
require_once  __DIR__ . "/../../../repositories/FoodRepository.php";
$rRepo = new RecipeRepository();
$fRepo = new FoodRepository();

if (isset($_POST['ingredient'])){
    $recipe = $rRepo->getOneById($_GET['id']);
    $deleted = $rRepo->deleteAllIngredients($recipe);
    if ($deleted){
        $res = $rRepo->setIngredients($_POST, $_GET['id']);
        if ($res){
            new SweetAlert(SweetAlert::SUCCESS,"Succès","Vos modifications ont bien été prises en compte");
        }else{
            new SweetAlert(SweetAlert::ERROR,"Erreur","Erreur lors de la prise en compte des modifications");
        }
    }else{
        new SweetAlert(SweetAlert::ERROR,"Erreur","Erreur lors de la prise en compte des modifications");
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (isset($_GET['id'])){
                $recipe = $rRepo->getOneById($_GET['id']);
                if ($recipe){
                    ?>
                    <h1 id="page-title"><?= translate("Modification de la recette");?> : <?= $recipe->getName();?></h1>
                    <form method="POST">
                        <div class="row">
                            <div class="col">
                                <p class="lead">Ingrédient</p>
                            </div>
                            <div class="col">
                                <p class="lead">Quantité</p>
                            </div>
                            <div class="col">
                                <p class="lead">Supprimer</p>
                            </div>
                        </div>
                        <?php
                        $allIngredients = $fRepo->getAll();

                        /* @var $ingredient Food */
                        foreach($recipe->getIngredients() as $ingredient){
                            ?>
                            <div class="row mb-4 ingredientRow" id="ingredient<?= $ingredient->getId();?>">
                                <div class="col">
                                    <select name="ingredient[]" class="form-control select2">
                                        <?php
                                        foreach($allIngredients as $food){
                                            if ($ingredient->getId() == $food->getId()){
                                                ?>
                                                <option selected value="<?= $food->getId();?>"><?= $food->getName();?></option>
                                                <?php
                                            }else{
                                                ?>
                                                <option value="<?= $food->getId();?>"><?= $food->getName();?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number"  name="ingredientQuantity[]" value="<?= $ingredient->getQuantity();?>" class="form-control">
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-danger" title="Supprimer l'ingrédient" data-toggle="tooltip" onclick="deleteIng(<?= $ingredient->getId();?>, this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </form>
                    <div id="ingredientPattern" style="display:none">
                        <div class="row mb-4 ingredientRow" id="ingredient">
                            <div class="col">
                                <select name="ingredient[]" class="form-control">
                                    <?php
                                    foreach($allIngredients as $food){
                                        ?>
                                        <option value="<?= $food->getId();?>"><?= $food->getName();?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" name="ingredientQuantity[]" value="<?= $ingredient->getQuantity();?>" class="form-control">
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-danger" title="Supprimer l'ingrédient" data-toggle="tooltip" onclick="deleteIng(<?= $ingredient->getId();?>,this)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <button class="btn btn-success" onclick="addIngredient()" title="Ajouter un ingrédient" data-toggle="tooltip">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-success" onclick="$('form').submit()">
                                Enregistrer mes modifications
                            </button>
                        </div>
                    </div>
                    <?php
                }else{

                }
            }else{
                echo translate("Veuillez sélectionner une recette");
            }
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function deleteIng(ing,elementIng){
        $(elementIng).parent().parent().remove();
        $('.tooltip').remove()
    }

    function addIngredient(){
        var pattern = $('#ingredientPattern').children();
        var copyPattern = pattern.clone();
        $('form').append(copyPattern);
        $('.tooltip').remove()
    }
</script>
