<?php
require_once __DIR__ . "/../../../repositories/MenuRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$mRepo = new MenuRepository();
$uRepo = new UserRepository();

// On récupère l'utilisateur courant
$user = $uRepo->getOneById($_COOKIE['user_id']);

?>
<title><?= translate("Espace Client");?> - <?= translate("Nouvelle commande");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Client");?> - <?= translate("Nouvelle commande");?>
    </h1>
    <div class="row">
        <div class="col-lg-8">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th><?= translate("Nom du menu");?></th>
                    <th><?= translate("Composé de");?></th>
                    <th><?= translate("Prix");?></th>
                    <th><?= translate("Actions");?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $menus = $mRepo->getAllFromTruck($_GET["truck_id"]);
                if ($menus){
                    foreach ($menus as $menu){
                        ?>
                        <tr>
                            <td><?= $menu->getName();?></td>
                            <td>
                            <form>
                            <?php
                            for($i = 0; $i < $menu->getRecipesNum(); $i++){
                            ?>
                                <div class="form-group">
                                    <label for="selectRecipe<?=$menu->getId().'_'.$i ?>"><?= translate("Plat numéro: ").($i+1);?></label>
                                    <select class="form-control" id="selectRecipe<?=$menu->getId().'_'.$i ?>">
                                    <?php
                                        for($j = 0; $j < count($menu->getRecipes()); $j++)echo '<option value="'.$menu->getRecipes()[$j]->getId().'">'.translate($menu->getRecipes()[$j]->getName()).'</option>';
                                    ?>
                                    </select>
                                </div>
                            <?php
                            }

                            for($i = 0; $i < $menu->getIngredientsNum(); $i++){
                                ?>
                                    <div class="form-group">
                                        <label for="selectIngredient<?=$menu->getId().'_'.$i ?>"><?= translate("Article numéro: ").($i+1);?></label>
                                        <select class="form-control" id="selectIngredient<?=$menu->getId().'_'.$i ?>">
                                        <?php
                                            for($j = 0; $j < count($menu->getIngredients()); $j++)echo '<option value="'.$menu->getIngredients()[$j]->getId().'">'.translate($menu->getIngredients()[$j]->getName()).'</option>';
                                        ?>
                                        </select>
                                    </div>
                                <?php
                                }
                            ?>
                            </form>
                            </td>
                            <td><?= $menu->getPrice()." €";?></td>
                            <td>
                                <button class="btn btn-primary" data-toggle="tooltip" title="<?= translate("Voir les ingrédients");?>" onclick="getMenu(<?= $menu->getId();?>)">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Ajouter au panier");?>" onclick="getMenu(<?= $menu->getId();?>)">
                                    <i class="fa fa-box"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                }else{
                    ?>
                    <tr style="background-color:lightgray">
                        <td colspan="4"><?= translate("Navré mais ce FoodTruck n'a pas de Menu à vous proposez.");?></td>
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
                        <th colspan="3"><i class="fas fa-shopping-basket"></i> <?=translate("Mon panier")?></th>
                    </tr>
                    <tr>
                        <th><i class="fas fa-utensils"></i> <?=translate("Article")?></th>
                        <th><i class="fas fa-sort-amount-down"></i> <?=translate("Quantité")?></th>
                        <th><?=translate("Actions")?></th>
                    </tr>
                </thead>
                <tbody id="basket">
                    <tr style="background-color:lightgray">
                        <td colspan="3"><?= translate("Votre panier est vide");?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var basket = [];
    var validateOrderButton = '<tr id="validateOrder"><td colspan="3" class="text-center"><a class="btn btn-success" onclick(createOrder(<?= $user->getId()?>))><?= translate('Passer la commande')?></a></td></tr>'; 
    $(window).bind('beforeunload', function(){
        return '<?= translate("Si vous quittez ou recharger cette page votre panier sera perdu !")?>';
    });
    function getMenu(idMenu){
        $.ajax({
            url : '/api/menu',
            type: 'GET',
            data: 'id=' + idMenu
        }).done(function(data){
            if(data.status == "success"){
                addMenu(data.Menu);
            }
        });
    }
    
    function addMenu(menu){
        var recipes = [];
        var ingredients = [];
        
        for(var i = 0; i < menu.recipes_num; i++){
            idRecipe = parseInt($('#selectRecipe' + menu.id + '_'+ i).val());
            recipe = {id : idRecipe, name: menu.recipes.find(x => x.id === idRecipe).name};
            recipes.push(recipe);
        }

        for(var j = 0; j < menu.ingredients_num; j++){
            var idIngre = parseInt($('#selectIngredient' + menu.id + '_'+ j).val());
            ingredient = {id: idIngre, name: menu.ingredients.find(x => x.id === idIngre).name};
            ingredients.push(ingredient);
        }

        menu.recipes = recipes;
        menu.ingredients = ingredients;
        menu.quantity = 1;
        
        noHash = menu.name+' : '
        var uniqueId = menu.id;

        for(var i = 0; i < menu.recipes_num; i++){
            noHash += menu.recipes[i].name+' + ';
            uniqueId += menu.recipes[i].id;
        }
        for(var j = 0; j < menu.ingredients_num -1 ; j++){
            noHash += menu.ingredients[j].name+' + ';
            uniqueId += menu.ingredients[j].id;
        }
        noHash += menu.ingredients[j].name;
        uniqueId += menu.ingredients[j].id;
        
        uniqueId = window.btoa(unescape(encodeURIComponent( menu.id+''+uniqueId ))).replace("=","");
        console.log(uniqueId);
        html = '<tr id="'+uniqueId+'"><td >'+ noHash;
        html += `</td><td id="`+uniqueId+`quantity">1</td><td><button class="btn btn-danger" title="<?= translate("Supprimer");?>"data-toggle="tooltip" onclick="deleteItem('`+uniqueId+`')"><i class="fas fa-trash"></i></button></td></tr>`;
        menu.unique_id = uniqueId;
        if($('#basket').html().indexOf('style="background-color:lightgray"') >= 0){
            $('#basket').html(html+validateOrderButton);
            basket.push(menu);
        }else{
            if($('#'+uniqueId).length != 0){
                var num = $('#'+uniqueId+'quantity').html();
                $('#'+uniqueId+'quantity').html(parseInt(num)+1);
                basket.find(x => x.unique_id === uniqueId).quantity += 1;
            }else{
                $('#validateOrder').remove();
                basketHtml = $('#basket').html();
                $('#basket').html(basketHtml+html+validateOrderButton);
                basket.push(menu);
            }
        }
        
    }

    function deleteItem(idItem){
        if($('#'+idItem).length != 0){
            var num = $('#'+idItem+'quantity').html();
            if(num > 1){
                $('#'+idItem+'quantity').html(parseInt(num)-1);
                basket.find(x => x.unique_id === idItem).quantity -= 1;
            }else{
                if($('#basket').children().length <= 2){
                    $('#'+idItem).remove();
                    $('#validateOrder').remove();
                    $('#basket').html('<tr style="background-color:lightgray"><td colspan="3"><?= translate("Votre panier est vide");?></td></tr>')
                }else{
                    $('#'+idItem).remove();
                }
                delete basket.find(x => x.unique_id === idItem);
            }
        }
    }

    async function createOrder(idMenu) {
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
                recipe: idMenu
            });
        }
    }
</script>