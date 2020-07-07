<?php

require_once __DIR__ . "/FoodRepository.php";

class StockRepository extends AbstractRepository
{
    public function getAllByFranchisee(User $franchisee): ?array{
        $rows = $this->dbManager->getAll("SELECT * FROM stock WHERE id_food_truck = ?",[
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