<?php
require_once __DIR__ . "/RecipeRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/FoodRepository.php";
require_once __DIR__ . "/../models/ClientOrder.php";

class ClientOrderRepository extends AbstractRepository
{

    public function getOneById(int $id):?ClientOrder
    {

        $order = $this->dbManager->find("SELECT * FROM client_order WHERE id = ?",[
            $id
        ]);
        if($order){
            $order_content = $this->dbManager->getAll("SELECT * FROM client_order_content WHERE id_order = ?",[
                $id
            ]);
            $menus = array();
            foreach($order_content as $content){
                $newMenu = array();
                $new = true;
                foreach($menus as $menu)if($menu['uuid'] == $content['uuid'])$new = false;
                if(!$new){
                    for($i = 0; $i < count($menus); $i ++){
                        if($menus[$i]['uuid'] == $content['uuid']){
                            if($content['id_recipe'] != NULL){
                                $menus[$i]["recipes"][] = ["id"=>$content['id_recipe']];
                            }else{
                                $menus[$i]["ingredients"][] = ["id"=>$content['id_food']];
                            }
                        }
                    }
                }else{
                    $newMenu += ["id"=>$content['id_menu']];
                    $newMenu += ["uuid"=>$content['uuid']];
                    $newMenu += ["quantity"=>$content['quantity']];
                    $newMenu += ["recipes" => []];
                    $newMenu += ["ingredients" => []];
                    if($content['id_recipe'] != NULL){
                        $newMenu["recipes"][] = ["id"=>$content['id_recipe']];
                    }else{
                        $newMenu["ingredients"][] = ["id"=>$content['id_food']];
                    }
                    $menus[] = $newMenu;
                }   
            }
            $order += ["menus"=> $menus];
            return new ClientOrder($order);
        }else{
            return NULL;
        }
    }

    public function add(array $menus, int $id_user, int $id_food_truck):ClientOrder{
        $uRepo = new UserRepository();
        $rows = $this->dbManager->exec("INSERT INTO client_order (id_user,id_food_truck,status) VALUES (?,?,?)",[
            $id_user,
            $id_food_truck,
            0
        ]);
        
        $orders = $this->dbManager->getAll("SELECT * FROM client_order WHERE id_user = ? &&  id_food_truck = ? ORDER BY date DESC",[
            $id_user,
            $id_food_truck
        ]);
        $newOrder = ["menus"=> $menus];
        $newOrder = array_merge($newOrder, $orders[0]);
        $order = new ClientOrder($newOrder);

        $user = $order->getUser();
        $userPoints = $user->getPoints();
        $user->setPoints($userPoints+intval($order->getTotalPrice()));
        $uRepo->update($user);
        foreach($order->getMenus() as $menu){
            foreach($menu->getRecipes() as $recipe){
                $rows = $this->dbManager->exec("INSERT INTO client_order_content (id_order, id_menu, id_recipe, quantity, uuid) VALUES (?,?,?,?, ?)",[
                    $order->getId(),
                    $menu->getId(),
                    $recipe->getId(),
                    $menu->getQuantity(),
                    $menu->getUuid()
                ]);
            }
            foreach($menu->getIngredients() as $ingredient){
                $rows = $this->dbManager->exec("INSERT INTO client_order_content (id_order, id_menu, id_food, quantity, uuid) VALUES (?,?,?,?,?)",[
                    $order->getId(),
                    $menu->getId(),
                    $ingredient->getId(),
                    $menu->getQuantity(),
                    $menu->getUuid()
                ]);
            }
        }  
        
        return $order;
    }

    public function getAllFromUser(User $client):array{
        $clientOrders = array();
        $orders = $this->dbManager->getAll("SELECT * FROM client_order WHERE id_user = ? ORDER BY date DESC",[
            $client->getId()
        ]);

        if($orders){
            foreach($orders as $order){
                $clientOrders[] = $this->getOneById($order['id']);
            }
        }
        return $clientOrders;
    }

    public function update(ClientOrder $order){
        $rows = $this->dbManager->exec('UPDATE client_order SET status =? WHERE id = ?', [
            $order->getStatus(),
            $order->getId()
        ]);
        return $rows == 1;
    }
}