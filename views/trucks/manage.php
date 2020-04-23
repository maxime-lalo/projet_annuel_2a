<?php require_once __DIR__ . "/../../repositories/UserRepository.php"; ?>

<title><?= translate("Gérer les camions");?></title>
<div class="container-fluid txt-container" style="margin-top:100px">

	<?php 

	$truckRepository = new UserRepository();

	var_dump($truckRepository->getOneById(2));
	?>
</div>
