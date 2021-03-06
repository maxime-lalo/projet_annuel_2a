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
        <span class="float-right">
            <div class="form-check form-check-inline">
                <h4><?= translate("Points fidélité: ")?></h4>
            </div>
            <div class="form-check form-check-inline">
                <h4 id="userPoints"><?= $user->getPoints()?></h4>
            </div>
        </span>
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
                $menus = $mRepo->getAllAvailableFromTruck($_GET["truck_id"]);
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
                        <th colspan="3">
                            <div class="form-check form-check-inline">
                                <h4><?= translate("Utilisez vos points acquis ? ")?></h4>
                            </div>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pointsUse" id="pointsYes" value="1">
                                <label class="form-check-label" for="pointsYes"><?= translate("Oui")?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pointsUse" id="pointsNo" value="0" checked>
                                <label class="form-check-label" for="pointsUse"><?= translate("Non")?></label>
                            </div>
                         </th>
                    </tr>
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
    var validateOrderButton = '<tr id="validateOrder"><td colspan="3" class="text-center"><a class="btn btn-success" onclick="createOrder(<?= $user->getId().','.$_GET['truck_id']?>)"><?= translate('Passer la commande')?></a></td></tr>'; 
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
        html = '<tr id="'+uniqueId+'"><td >'+ noHash;
        html += `</td><td id="`+uniqueId+`quantity">1</td><td><button class="btn btn-danger" title="<?= translate("Supprimer");?>"data-toggle="tooltip" onclick="deleteItem('`+uniqueId+`')"><i class="fas fa-trash"></i></button></td></tr>`;
        menu.uuid = uniqueId;
        if($('#basket').html().indexOf('style="background-color:lightgray"') >= 0){
            $('#basket').html(html+validateOrderButton);
            basket.push(menu);
        }else{
            if($('#'+uniqueId).length != 0){
                var num = $('#'+uniqueId+'quantity').html();
                $('#'+uniqueId+'quantity').html(parseInt(num)+1);
                basket.find(x => x.uuid === uniqueId).quantity += 1;
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
                basket.find(x => x.uuid === idItem).quantity -= 1;
            }else{
                if($('#basket').children().length <= 2){
                    $('#'+idItem).remove();
                    $('#validateOrder').remove();
                    $('#basket').html('<tr style="background-color:lightgray"><td colspan="3"><?= translate("Votre panier est vide");?></td></tr>')
                }else{
                    $('#'+idItem).remove();
                }
                basket = basket.filter(function( obj ) {
                    return obj.uuid !== idItem;
                });
            }
        }
    }

    async function createOrder(idUser, idTruck) {
        var totalPrice = 0;
        orderReview = '';
        $.each(basket, function (index, menu){
            orderReview +=
            '<tr>'+
                '<td>'+menu.name+'</td>'+
                '<td>';
                    $.each(menu.recipes, function (index, recipe){
                        orderReview += recipe.name+' + ';
                    })
                    for(var i = 0; i < menu.ingredients.length - 1; i++){
                        orderReview += menu.ingredients[i].name+' + ';
                    }
                    orderReview += menu.ingredients[i].name+
                '</td>';
                orderReview +=
                '<td>'+menu.quantity+'</td>'+
                '<td>'+parseFloat(menu.price)*parseFloat(menu.quantity)+' €</td>'+
            '</tr>';
            totalPrice += parseFloat(menu.price)*parseFloat(menu.quantity);
        })
        orderReview += 
        '<tr style="background-color:lightgray">'+
            '<td colspan="4">Prix Total: '+totalPrice+' €</td>'+
        '</tr>';
        Swal.fire({
            title: '<?= translate("Tout est bon pour vous ?")?>',
            html:
                    '<table class="table table-bordered text-center"> ' +
                        '<thead>' +
                            '<tr>' +
                                '<th><?= translate("Menu")?></th>' +
                                '<th><?= translate("Contient")?></th>' +
                                '<th><?= translate("Quantité")?></th>' +
                                '<th><?= translate("Prix")?> </th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            orderReview +
                        '</tbody>' +
                    '</table>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4AD710',
            cancelButtonColor: '#d33',
            cancelButtonText: '<?= translate("Annuler")?>',
            confirmButtonText: '<?= translate("Je valide ma commande !")?>'
            }).then((result) => {
                if (result.value) {
                    order = {id_user: idUser, id_food_truck: idTruck, menus: basket, use_points: $("input[name='pointsUse']:checked").val()}
                    console.log(JSON.stringify(order));
                    $.ajax({
                        url : '/api/order',
                        type: 'POST',
                        data: JSON.stringify(order)
                    }).done(function(data){
                        if(data.status == "success"){
                            basket = [];
                            
                            $('#validateOrder').remove();
                            $('#basket').html('<tr style="background-color:lightgray"><td colspan="3"><?= translate("Votre panier est vide");?></td></tr>')
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: '<?=translate('Votre commande a été transmise avec succès !')?>',
                                showConfirmButton: false,
                                timer: 2000
                            })
                            setTimeout(redirectToOrders, 2000);
                        }
                    });
                }
        })
    }

    function redirectToOrders(){
        window.location.href = "history";
    }
</script>