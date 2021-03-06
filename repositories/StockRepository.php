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

    public function modifyStock(int $idFood,int $quantity, User $user,string $action)
    {
        if ($action == "remove"){
            $stock = $this->dbManager->find("SELECT * FROM stock WHERE id_food = ? AND id_food_truck = ?",[
                $idFood,
                $user->getTruck()->getId()
            ]);

            if ($stock['quantity'] > $quantity){
                $sql = "UPDATE stock SET quantity = quantity - ? WHERE id_food = ? AND id_food_truck = ?";
            }else{
                $sql = "UPDATE stock SET quantity = ? WHERE id_food = ? AND id_food_truck = ?";
                $quantity = 0;
            }
        }else{
            $sql = "UPDATE stock SET quantity = quantity + ? WHERE id_food = ? AND id_food_truck = ?";

        }
        $rows = $this->dbManager->exec($sql,[
            $quantity,
            $idFood,
            $user->getTruck()->getId()
        ]);

        return $rows == 1;
    }

    public function getMissingFood(?User $user):?array
    {
        $sql = "SELECT * FROM food WHERE id NOT IN (SELECT a.id FROM food a LEFT JOIN stock b ON a.id = b.id_food WHERE id_food_truck = ?)";
        $rows = $this->dbManager->getAll($sql,[
            $user->getTruck()->getId()
        ]);

        if ($rows){
            $returnArray = [];
            foreach($rows as $row){
                $returnArray[] = new Food($row);
            }
            return $returnArray;
        }else{
            return null;
        }
    }

    public function addToStock(int $foodToAdd, ?User $user):bool
    {
        $rows = $this->dbManager->exec("INSERT INTO stock (id_food, id_warehouse, id_food_truck, quantity) VALUES (?,null,?,0)",[
            $foodToAdd,
            $user->getTruck()->getId()
        ]);

        return $rows == 1;
    }
}