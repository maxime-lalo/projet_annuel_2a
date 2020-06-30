<?php
require_once __DIR__ . "/../../../repositories/ClientOrderRepository.php";
$coRep = new ClientOrderRepository();
$order = $coRep->getOneById(55);
var_dump($order->getMenus()[1]);
?>