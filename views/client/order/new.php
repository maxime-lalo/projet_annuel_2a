<?php
require_once __DIR__ . "/../../../repositories/MenuRepository.php";

$mRepo = new MenuRepository();
$test = $mRepo->getAll();
var_dump($test);