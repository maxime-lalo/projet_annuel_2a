<?php
require_once __DIR__ . "/../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../repositories/StockRepository.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";
require_once __DIR__ . "/../../repositories/RecipeRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();
$sRepo = new StockRepository();
$rRepo = new RecipeRepository();
?>
<title><?= translate("Espace Franchisé");?> - <?= translate("Consulter mon stock");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Franchisé");?> - <?= translate("Consulter mon stock");?>
    </h1>
    <?php
    if ($user){
        ?>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th><?= translate("Article");?></th>
                <th><?= translate("Type");?></th>
                <th><?= translate("Stock");?></th>
                <th><?= translate("Poids unitaire");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $stock = $sRepo->getAllByFranchisee($user);
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

        <p class="lead"><?= translate("Avec ces ingrédients vous pouvez cuisiner");?> : </p>
        <table class="table table-bordered">
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
                        ?>
                        <tr>
                            <td><?= $recipe->getName();?></td>
                            <td><?= min($maximumCreatableQuantity);?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }else{
        echo translate("Veuillez vous connecter");
    }
    ?>
</div>
