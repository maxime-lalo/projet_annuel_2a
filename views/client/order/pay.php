<?php
require_once __DIR__ . "/../../../repositories/UserRepository.php";
require_once __DIR__ . "/../../../repositories/ClientOrderRepository.php";
$uRepo = new UserRepository();
$coRepo = new ClientOrderRepository();
$client = $uRepo->getOneById($_COOKIE['user_id']);

$order = $coRepo->getOneById($_GET['order_id']);
?>
<title><?= translate("Espace Client"); ?> - <?= translate("Payement"); ?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Client"); ?> - <?= translate("Payement"); ?>
    </h1>
    <?php
    if(!$order->isPayed()){
    ?>
    <div class="row">
        <div class="col-lg-4">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th colspan="4"><i class="fas fa-shopping-basket"></i> <?= translate("Commande numéro: ") . $order->getId() ?></th>
                    </tr>
                    <tr>
                        <th><?= translate("Menu")?></th>
                        <th><?= translate("Plat/Article")?></th>
                        <th><?= translate("Quantité")?></th>
                        <th><?= translate("Prix")?></th>
                    </tr>
                </thead>
                <tbody id="basket">
                <tbody>
                    <?php
                    foreach ($order->getMenus() as $menu) {
                    ?>
                        <tr>
                            <td rowspan="<?= $menu->getIngredientsNum() + $menu->getRecipesNum() ?>"><?= $menu->getName(); ?></th>
                                <?php
                                if (!empty($menu->getRecipes())) {
                                ?>
                            <td><?= $menu->getRecipes()[0]->getName(); ?></th>
                            <td rowspan="<?= $menu->getIngredientsNum() + $menu->getRecipesNum() ?>"><?= $menu->getQuantity(); ?></th>
                            <td rowspan="<?= $menu->getIngredientsNum() + $menu->getRecipesNum() ?>"><?= $menu->getPrice(); ?> €</th>
                            <?php
                                } else {
                            ?>
                            <td><?= $menu->getIngredients()[0]->getName(); ?></th>
                            <td rowspan="<?= $menu->getIngredientsNum() + $menu->getRecipesNum() ?>"><?= $menu->getQuantity(); ?></th>
                            <td rowspan="<?= $menu->getIngredientsNum() + $menu->getRecipesNum() ?>"><?= $menu->getPrice(); ?> €</th>
                            <?php
                                }
                            ?>
                        </tr>
                        <?php
                        if (!empty($menu->getRecipes())) {
                            for ($i = 0; $i < count($menu->getRecipes()) - 1; $i++) {
                        ?>
                                <tr>
                                    <td><?= $menu->getRecipes()[$i]->getName() ?></td>
                                </tr>
                            <?php
                            }
                            foreach ($menu->getIngredients() as $ingredient) {
                            ?>
                                <tr>
                                    <td><?= $ingredient->getName() ?></td>
                                </tr>
                            <?php
                            }
                        } else {
                            for ($i = 0; $i < count($menu->getIngredients()) - 1; $i++) {
                            ?>
                                <tr>
                                    <td><?= $menu->getIngredients()[$i]->getName() ?></td>
                                </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                </tbody>
                <tr style="background-color: #54F055">
                    <td colspan="4"><?= translate("Somme à payer: ").$order->getTotalPrice(); ?> €</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-8">
            <div class="col align-self-center">
                <form role="form">
                    <div class="form-group">
                        <label for="username">
                            <h4><?= translate("Propriétaire de la carte") ?></h4>
                        </label>
                        <input id="userName" type="text" name="name" placeholder="<?= translate("Nom du propriétaire de la carte") ?>" required class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">
                            <h4><?= translate("Numéro de carte") ?></h4>
                        </label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fab fa-cc-visa mx-1"></i>
                                    <i class="fab fa-cc-mastercard mx-1"></i>
                                    <i class="fab fa-cc-amex mx-1"></i>
                                </div>
                            </div>
                            <input id="cardNum" type="text" name="cardNumber" placeholder="<?= translate("Numéro de carte Valide") ?>" class="form-control " required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>
                                    <span class="hidden-xs">
                                        <h4><?= translate("Date d'expiration") ?></h4>
                                    </span>
                                </label>
                                <div class="input-group">
                                    <input id="cardMM" type="number" placeholder="MM" name="" class="form-control" required>
                                    <input id="cardYY" type="number" placeholder="YY" name="" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-4">
                                <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                    <h4>CVV
                                        <i class="fa fa-question-circle d-inline"></i>
                                    </h4>
                                </label>
                                <input id="cardCVV" type="text" required class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="subscribe btn btn-primary btn-block shadow-sm" onclick="makePayment()">
                            <?= translate("Confimer payement") ?>
                        </button>
                </form>
            </div>
        </div>
    </div>
    <?php
    }else{
    ?>
    <h4><?= translate("Votre commandé à déjà été payé !")?></h4>
    <?php
    }
    ?>
</div>

<script>
    function makePayment() {
        var name = $("#userName").val()
        var cardNumber = $("#cardNum").val()
        var cardMM = $("#cardMM").val()
        var cardYY = $("#cardYY").val()
        var cardCVV = $("#cardCVV").val()

        if ($.isNumeric(cardNumber) && $.isNumeric(cardMM) && $.isNumeric(cardYY) && $.isNumeric(cardCVV)) {
            payment = {
                card_number: cardNumber,
                card_month: cardMM,
                card_year: cardYY,
                user_id: <?= $user->getId();?>,
                id: <?= $order->getId();?>
            }
            $.ajax({
                url: '/api/order',
                type: 'PUT',
                data: JSON.stringify(payment)
            }).done(function(data) {
                if (data.status == "success") {
                    Swal.fire(
                        '<?= translate("Validé")?>',
                        '<?= translate("Votre Commande a été payé avec succès !")?>',
                        'success'
                    )
                    setTimeout(redirectToOrders, 1000);
                }
            });
        } else {
            Swal.fire(
                '<?= translate("Champs manquants")?>',
                '<?= translate("Veuillez remplir tous les champs !")?>',
                'error'
            )
        }
    };

    function redirectToOrders(){
        window.location.href = "history";
    }
</script>