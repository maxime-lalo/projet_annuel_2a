<?php
require_once __DIR__ . "/RecipeRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/FoodRepository.php";
require_once __DIR__ . "/FoodTruckRepository.php";
require_once __DIR__ . "/../models/FranchiseeOrder.php";

class FranchiseeOrderRepository extends AbstractRepository
{
    private int $id;

    private User $user;
    private Warehouse $warehouse;
    private DateTime $date;

    private array $foods;
    private array $missing;

    private array $recipes;
    private float $percentage;

    public function getOneById(int $id):?FranchiseeOrder
    {
        $order = $this->dbManager->find("SELECT * FROM franchisee_order WHERE id = ?",[
            $id
        ]);

        $foods = $this->dbManager->getAll('SELECT * FROM franchisee_order_content WHERE id_order = ?',[
            $id
        ]);

        $foodOrder = array();
        $fRepo = new FoodRepository();
        foreach($foods as $food){
            $foodToAdd = $fRepo->getOneById($food['id_food']);
            $foodToAdd->setQuantity($food['quantity']);

            $foodOrder[] = $foodToAdd;
        }

        $order['foods'] = $foodOrder;
        return new FranchiseeOrder($order);
    }

    public function createOrder(){
        $rRepo = new RecipeRepository();
        $uRepo = new UserRepository();
        $user = $uRepo->getOneById($_COOKIE['user_id']);

        if (isset($_SESSION['basket'])){
            $ingredientsList = array();

            foreach ($_SESSION['basket'] as $article){
                $recipe = $rRepo->getOneById($article['recipe']['id']);
                $quantity = $article['quantity'];

                foreach ($recipe->getIngredients() as $ingredient){
                    if (isset($ingredientsList[$ingredient->getId()])){
                        $actualQuantity = $ingredientsList[$ingredient->getId()]->getQuantity();
                        $ingredientsList[$ingredient->getId()]->setQuantity($actualQuantity + ($ingredient->getQuantity() * $quantity));
                    }else{
                        $ingredient->setQuantity($ingredient->getQuantity() * $quantity);
                        $ingredientsList[$ingredient->getId()] = $ingredient;
                    }
                }
            }

            $missingIngredients = array();
            $notMissingIngredients = array();

            $totalWeightMissing = 0;
            $totalWeightNotMissing = 0;

            foreach ($ingredientsList as $ingredient){
                $quantityNeeded = $ingredient->getQuantity() / $ingredient->getWeight();
                if ($quantityNeeded != intval($quantityNeeded)){
                    $quantityNeeded = intval($quantityNeeded)+1;
                }else{
                    $quantityNeeded = intval($quantityNeeded);
                }

                $res = $rRepo->checkFoodStock($ingredient, $user->getWarehouse());

                $ingredient->setQuantity($quantityNeeded);

                if ($res == null){
                    $totalWeightMissing += $ingredient->getWeight() * $ingredient->getQuantity();
                    $missingIngredients[] = $ingredient;
                }else{
                    if ($res - $quantityNeeded >= 0){
                        $totalWeightNotMissing += $ingredient->getWeight() * $ingredient->getQuantity();
                        $notMissingIngredients[] = $ingredient;
                    }else{
                        if ($res == 0){
                            $totalWeightMissing += $ingredient->getWeight() * $ingredient->getQuantity();
                            $missingIngredients[] = $ingredient;
                        }else{
                            $ingredient->setQuantity($res);
                            $totalWeightNotMissing += $ingredient->getWeight() * $ingredient->getQuantity();
                            $notMissingIngredients[] = $ingredient;

                            $ingredient2 = clone $ingredient;
                            $ingredient2->setQuantity(-($res - $quantityNeeded));

                            $totalWeightMissing += $ingredient->getWeight() * $ingredient->getQuantity();
                            $missingIngredients[] = $ingredient2;
                        }
                    }
                }
            }
            $totalWeight = $totalWeightMissing + $totalWeightNotMissing;
            $percentage = ($totalWeightMissing / $totalWeight) * 100;
            $header = $this->dbManager->exec("INSERT INTO franchisee_order (id_user, id_warehouse, percentage, missing) VALUES (?,?,?,?)",[
                $user->getId(),
                $user->getWarehouse()->getId(),
                number_format($percentage, 2, '.', ''),
                json_encode($missingIngredients)
            ]);

            $lastInsertId = $this->dbManager->getLastInsertId();
            foreach ($notMissingIngredients as $ingredient){
                $lines = $this->dbManager->exec("INSERT INTO franchisee_order_content (id_order, id_food, quantity) VALUES (?,?,?)",[
                    $lastInsertId,
                    $ingredient->getId(),
                    $ingredient->getQuantity()
                ]);

                $removeFromStock = $this->dbManager->exec("UPDATE stock SET quantity = quantity - ? WHERE id_food = ? AND id_warehouse = ?",[
                    $ingredient->getQuantity(),
                    $ingredient->getId(),
                    $user->getWarehouse()->getId()
                ]);
            }

            unset($_SESSION['basket']);
        }
    }

    public function getAllOrders(User $user):?array{
        $orders = $this->dbManager->getAll("SELECT * FROM franchisee_order WHERE id_user = ? ORDER BY date DESC",[
            $user->getId()
        ]);

        $fRepo = new FoodRepository();

        if ($orders){
            $returnVal = array();
            foreach($orders as $order){
                $foods = $this->dbManager->getAll("SELECT * FROM franchisee_order_content WHERE id_order = ?",[
                    $order['id']
                ]);

                $orderFoods = array();
                foreach ($foods as $food){
                    $foodObj = $fRepo->getOneById($food['id_food']);
                    $foodObj->setQuantity($food['quantity']);
                    $orderFoods[] = $foodObj;
                }

                $order['foods'] = $orderFoods;
                $returnVal[] = new FranchiseeOrder($order);
            }

            return $returnVal;
        }else{
            return null;
        }
    }

    

    public function confirmOrder(FranchiseeOrder $order):bool{
        if($order->getStatus() != 3){
            $ftRepo = new FoodTruckRepository();
            if($ftRepo->addOrderToStock($order)){
                $line = $this->dbManager->exec("UPDATE franchisee_order SET status = ? WHERE id = ? ",[
                    3,
                    $order->getId()
                ]);
                if($line == 1)return true;
            }
        }
        return false;
    }
}