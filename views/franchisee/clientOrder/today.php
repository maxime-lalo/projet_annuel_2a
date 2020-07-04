<?php
require_once __DIR__ . "/../../../repositories/ClientOrderRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
$coRep = new ClientOrderRepository();
$uRepo = new UserRepository();
$user = $uRepo->getOneById($_COOKIE['user_id']);
$accepting_orders = '';
$truckId = ($user->getTruck() instanceof FoodTruck)? $user->getTruck()->getId() : -1;
if($user->getTruck() instanceof FoodTruck && $user->getTruck()->getAcceptsOrders() == 1){
    $accepting_orders = 'checked';

}
?>

<title><?= translate("Espace Franchisee"); ?> - <?= translate("Commandes du jour"); ?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Franchisee"); ?> - <?= translate("Commandes du jour"); ?>
        <span  class="float-right">
        <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" onclick="acceptOrders('<?= $truckId; ?>')" id="acceptOrders" <?= $accepting_orders?>>
                    <label class="custom-control-label" for="acceptOrders" style="font-size: 0.4em;"><?= translate("Accepter des commandes ?")?></label>
            </div>
        </span>
        
    </h1>
    <div class="row">
        <div class="col col-lg-2">
        <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                <label class="form-check-label" for="autoSizingCheck2" style="font-size: 0.8em;"><?=translate("rafraichissement-auto")?></label>
            </div>
            
        </div>
        <span id="statusJS" class="float-right">
            
            <div class="spinner-border text-dark" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </span>
            
    </div>
    <div class="col-lg">
        <h2>Commandes en cours</h2>
        
        <div id="ordersInPrep" class="row">
        </div>
        <hr>
        <h2>Commandes en attente de préparation</h2>

        <div id="ordersInStandBy" class="row">
        </div>
        <hr>
        <h2>Commandes terminées</h2>
        <div id="ordersDone" class="row">
        </div>
    </div>
</div>

<script>
    var orderStatus = [<?php
                    for ($i = 0; $i < count(ORDER_STATUS) - 1; $i++) {
                        echo '"' . translate(ORDER_STATUS[$i]) . '",';
                    };
                    echo '"' . translate(ORDER_STATUS[$i]) . '"';
                    ?>];
    var mouseMove = false;
    var startTimer = Date.now() - 45000;
    $(document).mousemove(function(event) {
        mouseMove = true;
    });
    reloadData();

    function reloadData() {
        if (mouseMove && $("#autoRefresh").is(":checked")) {
            getTodayOrders();
            mouseMove = false
        } else {
            if((Date.now() - startTimer) >= 60000 && $("#autoRefresh").is(":checked")){
                getTodayOrders();
                mouseMove = false
            }else{
                setTimeout(reloadData, 1000);
            }
        }
    }

    function getTodayOrders() {
        $("#statusJS").html('<div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div>');
        $.ajax({
            url: '/api/order?user_id=<?= $user->getId(); ?>&date_range=today&user_type=worker',
            type: 'GET',
        }).done(function(data) {
            if (data.status == "success") {
                refreshPage(data.ClientOrders)
            }
        });
    }

    function refreshPage(orders){
        var ordersInPrep = '';
        var ordersInStandBy = '';
        var ordersDone = '';
        var orderTemp = '';
        $.each(orders, function(index, order){

            orderTemp = 
            '<div class="col-lg-4">'+
                '<table class="table table-bordered text-center">'+
                    '<thead>'+
                        '<tr>'+
                            '<th colspan="4"><i class="fas fa-shopping-basket"></i> <?= translate("Commande numéro: ")?> '+order.id+'</th>'+
                        '</tr>';
            if(order.status == 5){
                orderTemp += 
                        '<tr>'+
                            '<th colspan="4">'+
                                '<button class="btn btn-success" data-toggle="tooltip" title="<?= translate(ORDER_STATUS[3]); ?>" onclick="finishOrder('+order.id+')">'+
                                    '<i class="fa fa-check" style="margin-right: 5px;"></i><?= translate(ORDER_STATUS[3]);?> ?'+
                                '</button>'+
                            '</th>'+
                        '</tr>';
            }
            if(order.status == 1){
                orderTemp += 
                        '<tr>'+
                            '<th colspan="2">'+
                                '<button class="btn btn-primary" data-toggle="tooltip" title="<?= translate(ORDER_STATUS[5]); ?>" onclick="finishPrepOrder('+order.id+')">'+
                                    '<i class="fa fa-thumbs-up" style="margin-right: 5px;"></i> <?= translate(ORDER_STATUS[5]);?> ?'+
                                '</button>'+
                            '</th>'+
                            '<th colspan="2">'+
                                '<button class="btn btn-danger" data-toggle="tooltip" title="<?= translate(ORDER_STATUS[2]); ?>" onclick="cancelOrder('+order.id+')">'+
                                    '<i class="fa fa-close" style="margin-right: 5px;"></i><?= translate(ORDER_STATUS[2]);?> ?'+
                                '</button>'+
                            '</th>'+
                        '</tr>';
            } 
            if(order.status == 0){
                orderTemp += 
                        '<tr>'+
                            '<th colspan="2">'+
                                '<button class="btn btn-primary" data-toggle="tooltip" title="<?= translate(ORDER_STATUS[1]); ?>" onclick="prepareOrder('+order.id+')">'+
                                    '<i class="fa fa-gear" style="margin-right: 5px;"></i><?= translate(ORDER_STATUS[1]);?> ?'+
                                '</button>'+
                            '</th>'+
                            '<th colspan="2">'+
                                '<button class="btn btn-danger" data-toggle="tooltip" title="<?= translate(ORDER_STATUS[2]);?>" onclick="cancelOrder('+order.id+')">'+
                                    '<i class="fa fa-close" style="margin-right: 5px;"></i><?= translate(ORDER_STATUS[2]);?> ?'+
                                '</button>'+
                            '</th>'+
                        '</tr>';
            }  
            orderTemp +=
                        '<tr>'+
                            '<th><?= translate("Menu")?></th>'+
                            '<th><?= translate("Plat/Article")?></th>'+
                            '<th><?= translate("Quantité")?></th>'+
                            '<th><?= translate("Prix")?></th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody id="basket">'+
                    '<tbody>';
            $.each(order.menus, function(index, menu){
                orderTemp +=
                        '<tr>'+
                            '<td rowspan="'+parseInt(menu.ingredients_num+menu.recipes_num)+'">'+menu.name+'</th>';
                if(menu.recipes.length >=1){
                    orderTemp +=
                            '<td>'+menu.recipes[0].name+'</th>'+
                            '<td rowspan="'+parseInt(menu.ingredients_num+menu.recipes_num)+'">'+menu.quantity+'</th>'+
                            '<td rowspan="'+parseInt(menu.ingredients_num+menu.recipes_num)+'">'+parseFloat(menu.price*menu.quantity)+' €</th>';
                }else{
                    orderTemp +=
                            '<td>'+menu.ingredients[0].name+'</th>'+
                            '<td rowspan="'+parseInt(menu.ingredients_num+menu.recipes_num)+'">'+menu.quantity+'</th>'+
                            '<td rowspan="'+parseInt(menu.ingredients_num+menu.recipes_num)+'">'+parseFloat(menu.price*menu.quantity)+' €</th>';
                }
                orderTemp +=
                        '</tr>';
                if(menu.recipes.length >=1){
                    for(var i = 0; i < menu.recipes.length - 1; i++){
                        orderTemp +=
                        '<tr>'+
                            '<td>'+menu.recipes[i].name+'</td>'+
                        '</tr>';
                    }
                    $.each(menu.ingredients, function(index, ingredient){
                        orderTemp +=
                        '<tr>'+
                            '<td>'+ingredient.name+'</td>'+
                        '</tr>';
                    })
                }else{
                    for(var j = 0; j < menu.ingredients.length - 1; j++){
                        orderTemp +=
                        '<tr>'+
                            '<td>'+menu.ingredients[j].name+'</td>'+
                        '</tr>';
                    }
                }
                        
            })
            orderDate = new Date(order.date.date);
            orderTemp +=
                    '<tr>'+
                        '<td colspan="4">'+order.user.firstname + ' ' + order.user.lastname +'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td colspan="4"><?= translate("Commandé à ")?>'+orderDate.getHours() + 'h' + orderDate.getMinutes() +'</td>'+
                    '</tr>'+
                    '<tr style="background-color: #54F055">'+
                        '<td colspan="4"><?= translate("Somme payé: ")?>'+order.total_price+' €</td>'+
                    '</tr>'+
                    '</tbody>'+
                '</table>'+
            '</div>';
            //prep
            if(order.status == 1 || order.status == 5){
                ordersInPrep += orderTemp;
            }
            if(order.status == 0){
                ordersInStandBy += orderTemp;
            }
            if(order.status == 3 || order.status == 2){
                ordersDone += orderTemp;
            }
        })
        $("#ordersInPrep").html(ordersInPrep);
        $("#ordersInStandBy").html(ordersInStandBy);
        $("#ordersDone").html(ordersDone);

        $("#statusJS").html('<i class="fa fa-refresh" aria-hidden="true"></i>');
        startTimer = Date.now();
        setTimeout(() => {
            mouseMove = false;
            reloadData();
        }, 10000);
    }

    function cancelOrder(orderId){
        $.ajax({
            url: '/api/order',
            type: 'PUT',
            data: JSON.stringify({id: orderId, new_status: 2})
        }).done(function(data) {
            if (data.status == "success") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?=translate('Commande annulée !')?>',
                    showConfirmButton: false,
                    timer: 1000
                })
                getTodayOrders();
            }
        });
    }

    function prepareOrder(orderId){
        $.ajax({
            url: '/api/order',
            type: 'PUT',
            data: JSON.stringify({id: orderId, new_status: 1})
        }).done(function(data) {
            if (data.status == "success") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?=translate('Commande maintenant en préparation.')?>',
                    showConfirmButton: false,
                    timer: 1000
                })
                getTodayOrders();
            }
        });
    }

    function finishPrepOrder(orderId){
        $.ajax({
            url: '/api/order',
            type: 'PUT',
            data: JSON.stringify({id: orderId, new_status: 5})
        }).done(function(data) {
            if (data.status == "success") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?=translate('Préparation terminée.')?>',
                    showConfirmButton: false,
                    timer: 1000
                })
                getTodayOrders();
            }
        });
    }

    function finishOrder(orderId){
        $.ajax({
            url: '/api/order',
            type: 'PUT',
            data: JSON.stringify({id: orderId, new_status: 3})
        }).done(function(data) {
            if (data.status == "success") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?=translate('Commande terminée !')?>',
                    showConfirmButton: false,
                    timer: 1000
                })
                getTodayOrders();
            }
        });
    }

    function acceptOrders(idTruck){
        if($("#acceptOrders").is(":checked")){
            var acceptOrders = 1;
            var acceptMsg = '<?= translate("Vous acceptez maintenant des commandes !")?>';
        }else{
            var acceptOrders = 0;
            var acceptMsg = `<?= translate("Vous n'acceptez plus de commandes !")?>`;
        }
        $.ajax({
            url: '/api/truck',
            type: 'PUT',
            data: JSON.stringify({id: idTruck, accepts_orders: acceptOrders})
        }).done(function(data) {
            if (data.status == "success") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: acceptMsg,
                    showConfirmButton: false,
                    timer: 1000
                })
            }
        });
    }
</script>