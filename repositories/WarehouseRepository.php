<?php
require_once __DIR__ . "/../models/Warehouse.php";
require_once __DIR__ . "/AbstractRepository.php";

class WarehouseRepository extends AbstractRepository
{
    public function getFromUserId(int $userId){
    	$warehouse = $this->dbManager->find("SELECT * FROM user a INNER JOIN warehouse b ON a.warehouse_id = b.id WHERE a.id = ?",[ $userId ]);
    	return $warehouse == null ? null: new Warehouse($warehouse);
    }

    public function add(Warehouse $warehouse):?int{
        $rows = $this->dbManager->exec("INSERT INTO warehouse (name,street_name,street_number,zipcode, city) VALUES (?,?,?,?,?)",[
            $warehouse->getName(),
            $warehouse->getStreetName(),
            $warehouse->getStreetNumber(),
            $warehouse->getZipcode(),
            $warehouse->getcity()
        ]);
        
        if ($rows == 1){
            return $this->dbManager->getLastInsertId();
        }else{
            return null;
        }
    }

    public function update(Warehouse $warehouse):bool{
        $rows = $this->dbManager->exec('UPDATE warehouse SET name = ? WHERE id = ?',[
            $warehouse->getName(),
            $warehouse->getId()
        ]);
        return $rows == 1;
    }
}