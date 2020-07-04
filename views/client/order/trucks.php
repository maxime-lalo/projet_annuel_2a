<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$fRepo = new FoodTruckRepository();
$uRepo = new UserRepository();

$client = $uRepo->getOneById($_COOKIE['user_id']);



if(isset($_GET['addr'])){
    $origin_client = $_GET['addr'];
}else{
    $origin_client = $client->getStreetNumber().' '.$client->getStreetName().', '.$client->getCity();
}

$directions_url = 'https://www.google.com/maps/dir/?api=1&travelmode=walking&origin='.urlencode($origin_client).'&destination=';

?>
<title><?= translate("Espace Client");?> - <?= translate("Commandez !");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Client");?> - <?= translate("Commandez !");?>
        <?php
        if(isset($_GET['addr'])){
        ?>
            <span class="float-right">
                <a class="mb-2 btn btn-primary" href="order">
                    <i class="fa fa-home"></i> <?= translate("Livraison chez vous ?");?>
                </a>
            </span>
        <?php
        }
        ?>
    </h1>
    <div class="col-sm-6">
        <h4><?= translate("FoodTrucks proches de: ").'<i>'.$origin_client;?></i></h4>
    </div>
    <form method="get">
    <div class="col-sm-3">  
        <input type="text" name="addr" class="form-control" aria-label="Small" placeholder="Autre adresse..." aria-describedby="inputGroup-sizing-sm"> 
    </div>
    <div class="col-sm-3">
        <button type="submit" class="btn btn-primary"><?= translate("Rechercher")?></button>
    </div>
    </form>
    
    
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= translate("FoodTruck");?></th>
            <th><?= translate("Ville");?></th>
            <th><?= translate("Adresse");?></th>
            <th><?= translate("Distance");?></th>
            <th><?= translate("Actions");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $foodTrucks = $fRepo->getTrucksAvailable($origin_client);
        if ($foodTrucks != null){
            foreach ($foodTrucks as $foodTruck){
                ?>
                <tr id="row<?= $foodTruck->getId();?>">
                    <td><?= $foodTruck->getName();?></td>
                    <td><?= $foodTruck->getCity();?></td>
                    <td><?= $foodTruck->getStreetNumber().' '.$foodTruck->getStreetName();?></td>
                    <td><?= $foodTruck->getDistanceToClient()/1000;?> km</td>
                   
                    <td id="actions<?= $foodTruck->getId();?>">
                        <a class="btn btn-success" title="<?= translate("Ouvrir dans maps");?>" data-toggle="tooltip" href="<?= $directions_url.urlencode($foodTruck->getFullAddress())?>">
                            <i class="fas fa-map-pin"></i>
                        </a>
                        <a class="btn btn-primary" title="<?= translate("Commander en ligne");?>" data-toggle="tooltip" href="new?truck_id=<?= $foodTruck->getId()?>" >
                            <i class="fas fa-shopping-basket"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }else{
            ?>
                <tr style="background-color:lightgray">
                    <td colspan="7" class="text-center"><?= translate("Oops il semble qu'aucun Foodtruck ne soit disponible proche de votre position.");?></td>
                </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    
</div>