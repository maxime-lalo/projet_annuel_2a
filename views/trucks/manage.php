<?php
require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
?>
<title><?= translate("Gérer les camions");?></title>
<div class="container-fluid txt-container" style="margin-top:100px">
	<?php 
	$truckRepository = new FoodTruckRepository();
	var_dump($truckRepository->getAll());
	?>
</div>
