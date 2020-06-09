<?php

require_once __DIR__ . "/../models/Warehouse.php";
require_once __DIR__ . "/../models/Food.php";
require_once __DIR__ . "/AbstractRepository.php";

class FoodRepository extends AbstractRepository
{
    public function getAllWithStock(Warehouse $warehouse):?Array{
        $stocks = $this->dbManager->getAll("SELECT a.id,a.name,a.weight,a.type,b.quantity FROM food a INNER JOIN stock b ON a.id = b.id_food WHERE b.id_warehouse = ?",[
            $warehouse->getId()
        ]);

        $food = array();
        foreach ($stocks as $stock){
            $food[] = new Food($stock);
        }

        return $stocks ? $food:null;
    }
}