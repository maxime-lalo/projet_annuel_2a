<?php

require_once __DIR__ . "/../models/Warehouse.php";
require_once __DIR__ . "/../models/Food.php";
require_once __DIR__ . "/AbstractRepository.php";

class FoodRepository extends AbstractRepository
{
    public function getAllWithStock(Warehouse $warehouse):?Array{
        $stocks = $this->dbManager->getAll("SELECT a.id,a.name,a.weight,a.type,b.quantity,a.unity FROM food a INNER JOIN stock b ON a.id = b.id_food WHERE b.id_warehouse = ?",[
            $warehouse->getId()
        ]);

        $food = array();
        foreach ($stocks as $stock){
            $food[] = new Food($stock);
        }

        return $stocks ? $food:null;
    }
    public function setStock(int $idFood, int $idWarehouse, int $quantity){
        $rows = $this->dbManager->exec("UPDATE stock SET quantity = ? WHERE id_food = ? AND id_warehouse = ?",[
            $quantity,
            $idFood,
            $idWarehouse
        ]);

        return $rows == 1;
    }

    public function addFood(Food $food){
        $rows = $this->dbManager->exec("INSERT INTO food (name,weight,unity,type) VALUES (?,?,?,?)",[
            $food->getName(),
            $food->getWeight(),
            $food->getUnity(),
            $food->getType()
        ]);

        if ($rows == 1)
            return 1 ;
        else
            return NULL;
    }

    public function updateFood(Food $food){
        $rows = $this->dbManager->exec("UPDATE food SET name = ?,weight = ? ,unity = ?,type = ? WHERE id = ?",[
            $food->getName(),
            $food->getWeight(),
            $food->getUnity(),
            $food->getType(),
            $food->getId()
        ]);

        if ($rows == 1)
            return 1 ;
        else
            return NULL;
    }
}