<?php
require_once __DIR__ . "/../../../repositories/ClientOrderRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$coRep = new ClientOrderRepository();
$uRepo = new UserRepository();
$user = $uRepo->getOneById($_COOKIE['user_id']);

?>

<title><?= translate("Espace Client");?> - <?= translate("Historique commande");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Client");?> - <?= translate("Historique commande");?>
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
        <div class="col-lg">
            
            <?php
            $orders = $coRep->getAllFromUser($user);
            if($orders){
                foreach($orders as $order){
            ?>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th colspan="4" style="background-color:lightgray"><?= translate("Commande n°").$order->getId()?>
                            <button class="btn btn-primary" data-toggle="tooltip" title="<?= translate("Plus d'infos");?>" onclick="getOrder(<?= $order->getId()?>)">
                                <i class="fa fa-eye"></i>
                            </button>
                            <span class="float-right">
                                <h4 id="status<?= $order->getId()?>">
                                    <?php 
                                        echo translate(ORDER_STATUS[$order->getStatus()]);
                                    ?>
                                </h4>
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <th><?= translate("Menu")?></th>
                        <th><?= translate("Plat/Article")?></th>
                        <th><?= translate("Quantité")?></th>
                        <th><?= translate("Prix")?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($order->getMenus() as $menu){
                    ?>
                    <tr>
                        <td rowspan="<?= $menu->getIngredientsNum()+$menu->getRecipesNum()?>"><?= $menu->getName();?></th>
                            <?php
                            if(!empty($menu->getRecipes())){
                            ?>
                                <td><?= $menu->getRecipes()[0]->getName();?></th>
                                <td rowspan="<?= $menu->getIngredientsNum()+$menu->getRecipesNum()?>"><?= $menu->getQuantity();?></th>
                                <td rowspan="<?= $menu->getIngredientsNum()+$menu->getRecipesNum()?>"><?= $menu->getPrice();?> €</th>
                            <?php
                            }else{
                            ?>
                                <td><?= $menu->getIngredients()[0]->getName();?></th>
                                <td rowspan="<?= $menu->getIngredientsNum()+$menu->getRecipesNum()?>"><?= $menu->getQuantity();?></th>
                                <td rowspan="<?= $menu->getIngredientsNum()+$menu->getRecipesNum()?>"><?= $menu->getPrice();?> €</th>
                            <?php
                            }
                            ?>
                    </tr>
                        <?php
                        if(!empty($menu->getRecipes())){
                            for($i = 0; $i < count($menu->getRecipes())-1; $i++){
                        ?>
                    <tr>
                        <td><?=$menu->getRecipes()[$i]->getName()?></td>
                    </tr>
                    <?php
                            }
                            foreach($menu->getIngredients() as $ingredient){
                            ?>
                    <tr>
                        <td><?=$ingredient->getName()?></td>
                    </tr>
                            <?php
                            }
                        }else{
                            for($i = 0; $i < count($menu->getIngredients())-1; $i++){
                    ?>
                    <tr>
                        <td><?=$menu->getIngredients()[$i]->getName()?></td>
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
            }else{
            ?>
                <tr>
                    <h2><?=translate("Aucune commande")?></h2>
                </tr>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
    
    function getOrder(id){
        $.ajax({
            url : '/api/order',
            type: 'GET',
            data: 'id=' + id+ '&user_type=client'
        }).done(function(data){
            if(data.status == "success"){
                orderInfo(data.ClientOrder);
            }
        });
    }
    function orderInfo(order){
        order.date = new Date(order.date.date);
        var status = [<?php 
                for($i = 0; $i < count(ORDER_STATUS)-1; $i++){
                    echo '"'.translate(ORDER_STATUS[$i]).'",';
                };
                echo '"'.translate(ORDER_STATUS[$i]).'"';
                ?>];
        if(order.status != 0 && order.status != 4){
            Swal.fire({
                title: '<?= translate("Commande n°")?>'+order.id,
                html:
                        '<table class="table table-bordered text-center"> ' +
                            '<thead>' +
                                '<tr>' +
                                    '<th><?= translate("Prix Total")?></th>' +
                                    '<th><?= translate("Date")?></th>' +
                                    '<th><?= translate("Status")?></th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                                '<td>' + order.total_price + ' €</td>' +
                                '<td>' + order.date.getDay()+'/'+order.date.getMonth()+'/'+order.date.getFullYear()+' '+order.date.getHours() +':'+order.date.getMinutes()+'</td>' +
                                '<td>' + status[order.status] + '</td>' +
                            '</tbody>' +
                        '</table>'
                });
        }else{
            Swal.fire({
                title: '<?= translate("Commande n°")?>'+order.id,
                html:
                        '<table class="table table-bordered text-center"> ' +
                            '<thead>' +
                                '<tr>' +
                                    '<th><?= translate("Prix Total")?></th>' +
                                    '<th><?= translate("Date")?></th>' +
                                    '<th><?= translate("Status")?></th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                                '<td>' + order.total_price + ' €</td>' +
                                '<td>' + order.date.getDay()+'/'+order.date.getMonth()+'/'+order.date.getFullYear()+' '+order.date.getHours() +':'+order.date.getMinutes()+'</td>' +
                                '<td>' + status[order.status] + '</td>' +
                            '</tbody>' +
                        '</table>',
                confirmButtonColor: '#d33',
                confirmButtonText: '<?= translate("Annuler la commande")?>'
            }).then((result) => {
                if (result.value) {
                    data = {id: order.id, user_type: "client"};
                    $.ajax({
                        url : '/api/order',
                        type: 'DELETE',
                        data: JSON.stringify(data)
                    }).done(function(data){
                        if(data.status == "success"){
                            $('#status'+data.ClientOrder.id).html(status[data.ClientOrder.status]);
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: '<?=translate('Votre commande a été Annulé !');?>',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }
                        if(data.status == "error"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.info
                            })
                        }

                    });
                }
            });
        }
    }
</script>