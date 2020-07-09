<?php
require_once __DIR__ . "/../models/Recipe.php";
require_once __DIR__ . "/../models/Food.php";

class RecipeRepository extends AbstractRepository
{
    public function getOneById(int $id){
        $recipe = $this->dbManager->getAll("SELECT a.name as name_recipe,a.id as id_recipe,c.id,c.name,c.weight,c.type,b.quantity,b.unity FROM recipe a INNER JOIN recipe_ingredients b ON a.id = b.id_recipe INNER JOIN food c ON b.id_food = c.id WHERE a.id = ?",[
            $id
        ]);

        if ($recipe){
            $recipeArray = [
                'name' => $recipe[0]['name_recipe'],
                'id' => $recipe[0]['id_recipe'],
                'ingredients' => array()
            ];

            foreach ($recipe as $food){
                $recipeArray['ingredients'][] = new Food($food);
            }

            return new Recipe($recipeArray);
        }else{
            $recipe = $this->dbManager->find("SELECT * FROM recipe WHERE id = ?",[
                $id
            ]);
            if ($recipe){
                return $recipe;
            }
            return null;
        }
    }

    public function getAll():?array
    {
        $recipes = $this->dbManager->getAll('SELECT * FROM recipe');

        if ($recipes) {
            $return = array();
            foreach ($recipes as $recipe) {
                $ingredients = $this->dbManager->getAll('SELECT b.id,b.name,b.weight,b.type,a.unity,a.quantity FROM recipe_ingredients a INNER JOIN food b ON a.id_food = b.id WHERE a.id_recipe = ?', [
                    $recipe['id']
                ]);

                $ingredientsArray = array();
                foreach ($ingredients as $ingredient) {
                    $ingredientsArray[] = new Food($ingredient);
                }

                $return[] = new Recipe([
                    'id' => $recipe['id'],
                    'name' => $recipe['name'],
                    'ingredients' => $ingredientsArray
                ]);
            }

            return $return;
        } else {
            return null;
        }
    }

    public function checkRecipeStock(Recipe $recipe,Warehouse $warehouse,int $quantityOrdered = 1):array{
        $missing = array();
        foreach ($recipe->getIngredients() as $ingredient){
            $getStock = $this->dbManager->find("SELECT * FROM stock WHERE id_food = ? AND id_warehouse = ?",[
               $ingredient->getId(),
               $warehouse->getId()
            ]);

            if (!$getStock){
                $ingredient->setQuantity($quantityOrdered * $ingredient->getQuantity());
                $missing[] = $ingredient;
            }else{
                $quantityOrderedTtl = $ingredient->getQuantity() * $quantityOrdered;
                $quantityInStockTtl = $ingredient->getWeight() * $getStock['quantity'];

                if ($quantityOrderedTtl > $quantityInStockTtl){
                    $diff = $quantityOrderedTtl - $quantityInStockTtl;

                    $ingredient->setQuantity($diff);
                    $missing[] = $ingredient;
                }
            }
        }

        return $missing;
    }

    public function checkFoodStock(Food $food, Warehouse $warehouse):?int{
        $res = $this->dbManager->find("SELECT * FROM stock WHERE id_food = ? AND id_warehouse = ?",[
            $food->getId(),
            $warehouse->getId()
        ]);
        if (empty($res)){
            return null;
        }else{
            return $res['quantity'];
        }
    }

    public function deleteAllIngredients(int $id) :bool
    {
        $rows = $this->dbManager->exec("DELETE FROM recipe_ingredients WHERE id_recipe = ?",[
            $id
        ]);

        return $rows >= 0;
    }

    public function setIngredients(array $newIngredients, int $idRecipe):bool
    {
        $error = false;
        $fRepo = new FoodRepository();
        for ($i = 0; $i < count($newIngredients['ingredient']); $i++){
            $food = $fRepo->getOneById($newIngredients['ingredient'][$i]);

            $rows = $this->dbManager->exec("INSERT INTO recipe_ingredients (id_recipe, id_food, quantity, unity) VALUES (?,?,?,?)",[
                $idRecipe,
                $food->getId(),
                $newIngredients['ingredientQuantity'][$i],
                $food->getUnity()
            ]);

            if ($rows != 1){
                $error = true;
            }
        }

        return $error == true ? false:true;
    }

    public function create($recipeName):?int
    {
        $rows = $this->dbManager->exec("INSERT INTO recipe (name) VALUES (?)",[
            $recipeName
        ]);
        if ($rows == 1){
            return $this->dbManager->getLastInsertId();
        }else{
            return null;
        }
    }
}