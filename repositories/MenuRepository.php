<?php
require_once __DIR__ . "/../models/Menu.php";
require_once __DIR__ . "/RecipeRepository.php";
require_once __DIR__ . "/FoodRepository.php";
require_once __DIR__ . "/FoodTruckRepository.php";
require_once __DIR__ . "/AbstractRepository.php";

class MenuRepository extends AbstractRepository
{
    public function getAll():array{
        $rRepo = new RecipeRepository();
		$menus = $this->dbManager->getAll("SELECT * FROM menu");
        $return = array();
		foreach ($menus as $menu) {
            $recipes = $this->getAllRecipesFromMenu($menu["id"]);
            $ingredients = $this->getAllIngredientsFromMenu($menu["id"]);
            $keys = array("recipes", "ingredients");
            $values = array($recipes, $ingredients);
            $a = array_combine($keys, $values);
            $menu += $a;
			$return[] = new Menu($menu);
		}
		return $return;
    }

    public function getOneById(int $menuId):Menu{
        $menu = $this->dbManager->find("SELECT * FROM menu WHERE id = ?",[
            $menuId
        ]);
        $recipes = $this->getAllRecipesFromMenu($menu["id"]);
        $ingredients = $this->getAllIngredientsFromMenu($menu["id"]);
        $keys = array("recipes", "ingredients");
        $values = array($recipes, $ingredients);
        $a = array_combine($keys, $values);
        $menu += $a;
        return new Menu($menu);
    }

    public function getAllRecipesFromMenu(int $menuId):array
    {
        $rRepo = new RecipeRepository();
        $menuContents = $this->dbManager->getAll('SELECT * FROM menu_content WHERE id_menu = ? AND id_ingredient IS NULL',[
            $menuId
        ]);
        $return = array();
        foreach ($menuContents as $menuContent) {
            $recipe = $rRepo->getOneById($menuContent["id_recipe"]);
            $return[] = $recipe;
        }
        return $return;
    }

    public function getAllIngredientsFromMenu(int $menuId):array
    {
        $fRepo = new FoodRepository();
        $menuContents = $this->dbManager->getAll('SELECT * FROM menu_content WHERE id_menu = ? AND id_recipe IS NULL ',[
            $menuId
        ]);
        $return = array();
        foreach ($menuContents as $menuContent) {
            $ingredient = $fRepo->getOneById($menuContent["id_ingredient"]);
            $return[] = $ingredient;
        }
        return $return;
    }

    public function getAllFromTruck(int $truckId):array{
        $rRepo = new RecipeRepository();
		$menus = $this->dbManager->getAll("SELECT * FROM food_truck_has_menus WHERE id_foodtruck = ?",[
            $truckId
        ]);
        $return = array();
        foreach ($menus as $menuId) {
            $return[] = $this->getOneById($menuId["id_menu"]);
        }
		return $return;
    }

    public function getAllAvailableFromTruck(int $truckId):array{
        $availableStock = array();
        $ftRepo = new FoodTruckRepository();
        $foodTruckStock = $ftRepo->getStock($truckId);
        $menus = $this->getAllFromTruck($truckId);
        if(!empty($foodTruckStock) && !empty($menus)){
            foreach($menus as $menu){
                $checkRecipesAv = true;
                $checkIngredientsAv = true;
                foreach($menu->getRecipes() as $recipe){
                    $checkAv = false;
                    foreach($recipe->getIngredients() as $ingredient){
                        foreach($foodTruckStock as $ingredientAv){
                            if($ingredientAv->getId() == $ingredient->getId()){
                                $checkAv = true;
                                if($ingredientAv->getQuantity() < $ingredient->getQuantity()){
                                    $checkAv = false;
                                }
                            }
                        }
                    }
                    
                    if(!$checkAv) $checkRecipesAv = false;
                }
                foreach($menu->getIngredients() as $ingredient){
                    $checkAv = false;
                    foreach($foodTruckStock as $ingredientAv){
                        if($ingredientAv->getId() == $ingredient->getId()){
                            $checkAv = true;
                            if($ingredientAv->getQuantity() < $ingredient->getQuantity()){
                                $checkAv = false;
                            }
                        }
                    }
                    if(!$checkAv)$checkIngredientsAv = false;
                }
                if($checkIngredientsAv && $checkRecipesAv)$availableStock[] = $menu;
            }
        }
		return $availableStock;
    }

    public function add(Menu $menu):?int{
        $rows = $this->dbManager->exec("INSERT INTO menu (name,price,recipes_num,ingredients_num) VALUES (?,?,?,?)",[
            $menu->getName(),
            $menu->getPrice(),
            $menu->getRecipesNum(),
            $menu->getIngredientsNum()
        ]);

        if ($rows == 1){
            $idMenu = $this->dbManager->getLastInsertId();
            if($menu->getIngredients() != null)
            foreach($menu->getIngredients() as $ingredient) {
                $rows = $this->dbManager->exec("INSERT INTO menu_content (id_menu,id_ingredient,id_recipe) VALUES (?,?,?)", [
                    $idMenu,
                    $ingredient->getId(),
                    null
                ]);
            }
            if($menu->getRecipes() != null)
            foreach($menu->getRecipes() as $recipe) {
                $rows = $this->dbManager->exec("INSERT INTO menu_content (id_menu,id_ingredient,id_recipe) VALUES (?,?,?)", [
                    $idMenu,
                    null,
                    $recipe->getId()
                ]);
            }
            return 1;
        }
        else{
            return null;
        }
    }
    


    public function update(Menu $menu):?int{

        $rows = $this->dbManager->exec("DELETE FROM menu_content WHERE id_menu = ?",[
            $menu->getId()
        ]);

        
        $rows = $this->dbManager->exec("UPDATE menu SET name =?, price =?, recipes_num =?, ingredients_num =? WHERE id = ?",[
            $menu->getName(),
            $menu->getPrice(),
            $menu->getRecipesNum(),
            $menu->getIngredientsNum(),
            $menu->getId()
        ]);

      
            $idMenu = $menu->getId();
            if($menu->getIngredients() != null){
            $ingredients = $menu->getIngredients();
            foreach($ingredients as $ingredient) {
                $rows = $this->dbManager->exec("INSERT INTO menu_content (id_menu,id_ingredient,id_recipe) VALUES (?,?,?)", [
                    $idMenu,
                    $ingredient->getId(),
                    null
                ]);
            }
        }
            if($menu->getRecipes() != null){
            foreach($menu->getRecipes() as $recipe) {
                $rows = $this->dbManager->exec("INSERT INTO menu_content (id_menu,id_ingredient,id_recipe) VALUES (?,?,?)", [
                    $idMenu,
                    null,
                    $recipe->getId()
                ]);
            }
        }

            return 1;

        
    
    
    }

    public function deletebyId(int $id):?int {
        $rows = $this->dbManager->exec("DELETE FROM menu_content WHERE id_menu = ?",[
            $id
        ]);
        $rows = $this->dbManager->exec("DELETE FROM food_truck_has_menus WHERE id_menu = ?",[
            $id
        ]);
            $rows = $this->dbManager->exec("DELETE FROM menu WHERE id = ?",[
                $id
            ]);
            if ($rows == 1){
                return 1;
            }
            else return null;
        }

}

