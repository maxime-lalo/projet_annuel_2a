<?php

require_once __DIR__ . "/FoodRepository.php";

class StockRepository extends AbstractRepository
{
    public function getAllByFranchisee(User $franchisee): ?array{
        $rows = $this->dbManager->getAll("SELECT * FROM stock a INNER JOIN food b ON a.id_food = b.id WHERE id_food_truck = ? ORDER BY b.type",[
            $franchisee->getTruck()->getId()
        ]);

        if ($rows){
            $foodRepo = new FoodRepository();
            $returnArray = [];
            foreach($rows as $row){
                $food = $foodRepo->getOneById($row['id_food']);

                if ($food){
                    $food->setQuantity($row['quantity']);
                    $returnArray[] = $food;
                }
            }
            return $returnArray;
        }else{
            return null;
        }
    }
}