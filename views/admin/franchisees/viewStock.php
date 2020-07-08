<?php
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../../repositories/StockRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();
$sRepo = new StockRepository();
$rRepo = new RecipeRepository();
?>
<title><?= translate("Consulter le stock des franchisés");?> - <?= translate("Consulter le stock des franchisés");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Consulter le stock des franchisés");?>
        <span class="float-right">
            <a href="../manageFranchisees" class="btn btn-primary mb-2"><?= translate("Retour à la gestion des franchisés");?></a>
        </span>
    </h1>
    <?php
        $franchisee = $uRepo->getOneById($_GET['id']);
        if ($franchisee){
            ?>
            <p class="lead"><?= translate("Stock du franchisé");?> : <?= $franchisee->getFirstName()." ".$franchisee->getLastName();?></p>
            <table class="table table-bordered table-striped" data-toggle="table" data-search="true">
                <thead>
                    <tr>
                        <th data-sortable="true" data-field="name"><?= translate("Article");?></th>
                        <th data-sortable="true" data-field="type"><?= translate("Type");?></th>
                        <th><?= translate("Stock");?></th>
                        <th><?= translate("Poids unitaire");?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stock = $sRepo->getAllByFranchisee($franchisee);
                    if ($stock){
                        /* @var $food Food */
                        foreach ($stock as $food){
                            ?>
                            <tr>
                                <td><?= $food->getName();?></td>
                                <td><?= $food->getType();?></td>
                                <td><?= $food->getQuantity();?></td>
                                <td><?= $food->getWeight().$food->getUnity();?></td>
                            </tr>
                            <?php
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="3"><?= translate("Vous n'avez pas encore passé de commande");?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <p class="lead"><?= translate("Avec ces ingrédients le franchisé peut cuisiner");?> : </p>
            <table class="table table-bordered table-striped">
                <thead>
                <th><?= translate("Recette");?></th>
                <th><?= translate("Quantité fabriquable");?></th>
                </thead>
                <tbody>
                <?php
                $allRecipes = $rRepo->getAll();
                if ($allRecipes) {
                    /* @var $recipe Recipe */
                    foreach ($allRecipes as $recipe) {
                        $maximumCreatableQuantity = [];
                        /* @var $ingredient Food */
                        foreach ($recipe->getIngredients() as $ingredient) {
                            /* @var $foodInStock Food */
                            foreach ($stock as $foodInStock) {
                                if ($ingredient->getId() == $foodInStock->getId()) {
                                    $requiredQuantityForRecipe = $ingredient->getWeight();
                                    $availableQuantity = $foodInStock->getQuantity() * $foodInStock->getWeight();

                                    $maximumCreatableQuantity[] = intval($availableQuantity / $requiredQuantityForRecipe);
                                }
                            }
                        }

                        if (min($maximumCreatableQuantity) > 0){
                            ?>
                            <tr>
                                <td><?= $recipe->getName();?></td>
                                <td><?= min($maximumCreatableQuantity);?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        <?php
    }
    ?>
</div>
