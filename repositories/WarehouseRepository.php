<?php
require_once __DIR__ . "/../models/Warehouse.php";
require_once __DIR__ . "/AbstractRepository.php";

class WarehouseRepository extends AbstractRepository
{
    public function getFromUserId(int $userId){
    	$warehouse = $this->dbManager->find("SELECT * FROM user a INNER JOIN WAREHOUSE b ON a.warehouse_id = b.id WHERE a.id = ?",[ $userId ]);
    	return $warehouse == null ? null: new Warehouse($warehouse);
    }
}