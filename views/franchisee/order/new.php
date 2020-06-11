<?php
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
$rRepo = new RecipeRepository();
$uRepo = new UserRepository();
$fORepo = new FranchiseeOrderRepository();

// On récupère l'utilisateur courant
$user = $uRepo->getOneById($_COOKIE['user_id']);

// Si on valide le panier
if (isset($_GET['validateBasket'])){
    $fORepo->createOrder();
}

// Si on supprime un article du panier
if(isset($_GET['removeBasket'])){
    // On vérifie que l'article est bien présent dans le panier
    if (isset($_SESSION['basket'][$_GET['removeBasket']])){
        // On le supprime du panier
        unset($_SESSION['basket'][$_GET['removeBasket']]);

        // Si le panier est vide, on supprime la variable du panier entier
        if (empty($_SESSION['basket'])){
            unset($_SESSION['basket']);
        }

        // On lance une SweetAlert de succès de suppression
        new SweetAlert(SweetAlert::SUCCESS,"Succès","L'article a bien été retiré du panier");
    }
}

// Après validation de l'utilisateur, on ajoute le produit au panier
if (isset($_POST['addToBasket']) && isset($_POST['recipeToAdd']) && isset($_POST['quantity'])){
    // Si le produit est déjà dans le panier, on ajoute simplement la quantité saisie à la quantité déjà présente
    if (isset($_SESSION['basket'][$_POST['recipeToAdd']])){
        $_SESSION['basket'][$_POST['recipeToAdd']]['quantity'] += $_POST['quantity'];
    }else{
        // Sinon on rajoute l'article dans le panier
        $recipe = $rRepo->getOneById($_POST['recipeToAdd']);
        $_SESSION['basket'][$_POST['recipeToAdd']]['recipe']['id'] = $recipe->getId();
        $_SESSION['basket'][$_POST['recipeToAdd']]['recipe']['name'] = $recipe->getName();
        $_SESSION['basket'][$_POST['recipeToAdd']]['quantity'] = $_POST['quantity'];
    }
}

// Si on souhaite ajouter un article à son panier
if (isset($_POST['recipe']) && isset($_POST['quantity'])){
    // On récupère la recette, et on vérifie si l'on a le stock disponible pour tous les ingrédients associés
    $recipe = $rRepo->getOneById($_POST['recipe']);
    $stockAvailable = $rRepo->checkRecipeStock($recipe,$user->getWarehouse(),$_POST['quantity']);

    // Si tout est en stock, on ajoute directement l'article au panier
    if (empty($stockAvailable)){
        $_SESSION['basket'][$_POST['recipe']]['recipe']['id'] = $recipe->getId();
        $_SESSION['basket'][$_POST['recipe']]['recipe']['name'] = $recipe->getName();
        $_SESSION['basket'][$_POST['recipe']]['quantity'] = $recipe;
        new SweetAlert(SweetAlert::SUCCESS,"Succès","Le plat a bien été ajouté à votre panier");
    }else{
        // Sinon on affiche un tableau des articles manquants, et on demande à l'utilisateur s'il souhaite commander quand même
        $html = "<table class='table table-bordered'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>Ingrédient</th>";
        $html .= "<th>Quantité</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach ($stockAvailable as $missingIngredient){
            $html .= "<tr>";
            $html .= "<td>" . $missingIngredient->getName() . "</td>";
            $html .= "<td>" . $missingIngredient->getQuantity() . $missingIngredient->getUnity() . "</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='lead'>Des ingrédients sont manquants, souhaitez vous commander les produits disponibles, et acheter les produits manquants de votre côté ?</p>";

        ?>
        <script type="text/javascript">
            Swal.fire({
                title: 'Ingrédients manquants',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ajouter et compléter',
                cancelButtonText: 'Ne pas ajouter',
                cancelButtonColor: '#d33',
                html: "<?= $html;?>"
            }).then((result) => {
                if (result.value) {
                    data = {
                        addToBasket: true,
                        recipeToAdd: "<?= $_POST['recipe'];?>",
                        quantity: "<?= $_POST['quantity'];?>"
                    }
                    redirectPost("",data);
                }else{
                    Swal.fire(
                        'Annulé',
                        'Le produit n\'a pas été ajouté',
                        'info'
                    )
                }
            })
        </script>
        <?php
    }
}
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Nouvelle commande");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Nouvelle commande");?>
        <span class="float-right">
            <a href="history" class="btn btn-primary mb-2"><i class="fas fa-history"></i> <?= translate("Historique des commandes");?></a>
        </span>
    </h1>
    <p class="lead">
        <?= translate("Pour vous simplifier la vie, il suffit de commander une recette pour commander tous les produits qui y sont associés !");?>
    </p>
    <p class="lead" style="font-size:14px">
        <i class="fas fa-info"></i> <?= translate("Quand les clients commandent en ligne ce sont ces plats qu'ils voient");?>
    </p>
    <div class="row">
        <div class="col-lg-8">
            <table class="table table-bordered text-center">
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
                                <button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Ajouter au panier");?>" onclick="createOrder(<?= $recipe->getId();?>)">
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
        <div class="col-lg-4">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th colspan="3"><i class="fas fa-shopping-basket"></i> Mon panier</th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-utensils"></i> Article</th>
                        <th><i class="fas fa-sort-amount-down"></i> Quantité</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['basket'])){
                        foreach ($_SESSION['basket'] as $article){
                            ?>
                            <tr>
                                <td><?= $article['recipe']['name'];?></td>
                                <td><?= $article['quantity'];?></td>
                                <td>
                                    <a class="btn btn-danger" data-toggle="tooltip" title="Retirer du panier" href="?removeBasket=<?= $article['recipe']['id'];?>">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <a class="btn btn-success" href="?validateBasket=true">
                                    Passer la commande
                                </a>
                            </td>
                        </tr>
                        <?php
                    }else{
                        ?>
                        <tr>
                            <td colspan="3">Votre panier est vide</td>
                        </tr>
                        <?php
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

    async function createOrder(idRecipe) {
        const {value: quantity} = await Swal.fire({
            title: 'Combien voulez-vous en commander ?',
            input: 'number',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-plus"></i> Ajouter au panier',
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
                quantity: quantity,
                recipe: idRecipe
            });
        }
    }
</script>