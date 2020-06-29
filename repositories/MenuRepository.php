<?php
require_once __DIR__ . "/../models/Menu.php";
require_once __DIR__ . "/RecipeRepository.php";
require_once __DIR__ . "/FoodRepository.php";
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
    /*
    public function getAllAvailableFromTruck(int $truckId):array{
        $menus = $this->getAllFromTruck($truckId);
        $rRepo = new RecipeRepository();
        foreach($menus){

        }
		$menus = $this->dbManager->getAll("SELECT * FROM foodtruck_has_menus WHERE id_foodtruck = ?",[
            $truckId
        ]);
        $return = array();
        foreach ($menus as $menuId) {
            $return[] = $this->getOneById($menuId["id_menu"]);
        }
		return $return;
    }*/
    
}