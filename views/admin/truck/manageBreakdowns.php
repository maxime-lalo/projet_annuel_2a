<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$tRepo = new FoodTruckRepository();
$uRepo = new UserRepository();

if (isset($_POST['price']) && isset($_POST['description']) && isset($_POST['breakdown'])){
    $res = $tRepo->sendBreakdownBill($_POST['price'], $_POST['description'], $_POST['breakdown']);
    if ($res){
        new SweetAlert(SweetAlert::SUCCESS,"Succès","La facture a bien été envoyée, le franchisé n'a plus qu'à payer");
    }
}
if (isset($_POST['breakdown']) && is_numeric($_POST['breakdown']) && isset($_POST['action'])){
    if ($_POST['action'] == "process"){
        $res = $tRepo->processBreakdown($_POST['breakdown']);
        if ($res){
            new SweetAlert(SweetAlert::SUCCESS,"Succès","La panne a bien été prise en compte");
        }
    }
}
?>
<title><?= translate("Espace Admin");?> - <?= translate("Gérer les pannes");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gérer les pannes");?>
    </h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= translate("Id");?></th>
                <th><?= translate("Date");?></th>
                <th><?= translate("Camion");?></th>
                <th><?= translate("Franchisé");?></th>
                <th><?= translate("État");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $states = [
                0 => translate("Panne déclarée"),
                1 => translate("Panne prise en compte"),
                2 => translate("Panne traitée et à payer"),
                3 => translate("Panne terminée")
            ];
            $breakdowns = $tRepo->getAllBreakdowns();
            foreach($breakdowns as $breakdown){
                $truck = $tRepo->getOneById($breakdown['food_truck_id']);
                $franchisee = $uRepo->getOneById($breakdown['user_id']);
                ?>
                <tr>
                    <td><?= $breakdown['id'];?></td>
                    <td><?= date("d/m/Y H:i",strtotime($breakdown['date']));?></td>
                    <td><?= $truck->getId()." | ".$truck->getBrand()." ".$truck->getModel();?></td>
                    <td><?= $franchisee->getFirstname() . " " . $franchisee->getLastname();?></td>
                    <td><?= $states[$breakdown['state']];?></td>
                    <td>
                        <?php
                        if ($breakdown['state'] == 0){
                            ?>
                            <button type="button" class="btn btn-primary" onclick="processBreakdown(<?= $breakdown['id'];?>)"><?= translate("Prendre en compte");?></button>
                            <?php
                        }elseif($breakdown['state'] == 1){
                            ?>
                            <button type="button" class="btn btn-primary" onclick="sendBill(<?= $breakdown['id'];?>)"><?= translate("Envoyer la facture");?></button>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function sendBill(breakdown){
        Swal.mixin({
            input: 'text',
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            progressSteps: ['1', '2']
        }).queue([
            'Prix',
            'Description',
        ]).then((result) => {
            if (result.value) {
                data = {
                    price: result.value[0],
                    description: result.value[1],
                    breakdown: breakdown
                }
                redirectPost("",data);
            }
        })
    }

    function processBreakdown(breakdown){
        data = {
            breakdown: breakdown,
            action: "process"
        };
        redirectPost("",data);
    }
</script>