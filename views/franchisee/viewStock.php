<?php
require_once __DIR__ . "/../../repositories/FranchiseeOrderRepository.php";
require_once __DIR__ . "/../../repositories/StockRepository.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";
require_once __DIR__ . "/../../repositories/RecipeRepository.php";
$fORepo = new FranchiseeOrderRepository();
$uRepo = new UserRepository();
$sRepo = new StockRepository();
$rRepo = new RecipeRepository();

if (isset($_POST['idFood']) && isset($_POST['quantity']) && isset($_POST['action'])){
    $res = $sRepo->modifyStock($_POST['idFood'],$_POST['quantity'],$user,$_POST['action']);
    if ($res){
        new SweetAlert(SweetAlert::SUCCESS,"Succès","La quantité a bien été mise à jour");
    }else{
        new SweetAlert(SweetAlert::ERROR,"Erreur","Erreur lors de la modification du stock");
    }
}
if (isset($_POST['foodToAdd'])){
    $res = $sRepo->addToStock($_POST['foodToAdd'],$user);
    if ($res){
        new SweetAlert(SweetAlert::SUCCESS,"Succès","L'article a bien été ajouté");
    }else{
        new SweetAlert(SweetAlert::ERROR,"Erreur","Erreur lors de l'ajout de l'article");
    }
}
?>
<title><?= translate("Espace Franchisé");?> - <?= translate("Consulter mon stock");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Franchisé");?> - <?= translate("Consulter mon stock");?>
    </h1>
    <?php
    if ($user){
        ?>
        <table class="table table-bordered table-striped">
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
                        <td class="text-center">
                            <span class="float-left">
                                <button class="btn btn-danger" title="<?= translate('Enlever du stock');?>" data-toggle="tooltip" onclick="modifyStock(<?= $food->getId();?>,'remove')">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
                            <?= $food->getQuantity();?>
                            <span class="float-right">
                                <button class="btn btn-success" title="<?= translate('Ajouter du stock');?>" data-toggle="tooltip" onclick="modifyStock(<?= $food->getId();?>,'add')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </span>
                        </td>
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
        $missingFood = $sRepo->getMissingFood($user);
        if ($missingFood){
            ?>
            <p class="lead"><?= translate("Ajouter à mon stock");?></p>
            <form method="POST">
                <div class="form-group">
                    <select name="foodToAdd" id="foodToAdd" class="form-control">
                        <option value="null" selected disabled>--- <?= translate("Sélectionnez un article");?> ---</option>
                        <?php
                        /* @var $food Food */
                        foreach ($missingFood as $food){
                            ?>
                            <option value="<?= $food->getId();?>"><?= $food->getName();?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Ajouter à mon stock">
                </div>
            </form>
            <?php
        }
    }else{
        echo translate("Veuillez vous connecter");
    }
    ?>
</div>
<script type="text/javascript">
    async function modifyStock(idFood,action) {
        if (action === "remove"){
            var title = 'Combien de cet article souhaitez-vous enlever au stock ?';
        }else{
            var title = 'Combien de cet article souhaitez-vous ajouter au stock ?';

        }
        const {value: quantity} = await Swal.fire({
            title: title,
            input: 'number',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-plus"></i> Ajouter',
            showLoaderOnConfirm: true,
            cancelButtonText: '<i class="fa fa-times"></i> Annuler',
            inputValidator: (quantity) => {
                if (!quantity || quantity <= 0) {
                    return "Vous devez saisir une quantité valide"
                }
            }
        })

        if (quantity){
            redirectPost("",{
                idFood: idFood,
                quantity: quantity,
                action: action
            });
        }
    }

    $(document).ready(function() {
        $('#foodToAdd').select2();
    });
</script>
