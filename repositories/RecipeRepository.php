<?php
require_once __DIR__ . "/../models/Recipe.php";
require_once __DIR__ . "/../models/Food.php";

class RecipeRepository extends AbstractRepository
{
    public function getOneById(int $id):?Recipe{
        $recipe = $this->dbManager->getAll("SELECT a.name as name_recipe,a.id as id_recipe,c.id,c.name,c.weight,c.type,b.quantity FROM recipe a INNER JOIN recipe_ingredients b ON a.id = b.id_recipe INNER JOIN food c ON b.id_food = c.id WHERE a.id = ?",[
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
            return null;
        }
    }

    public function getAll():?array{
        $recipes = $this->dbManager->getAll('SELECT * FROM recipe');

        if ($recipes){
            $return = array();
            foreach ($recipes as $recipe){
                $ingredients = $this->dbManager->getAll('SELECT b.id,b.name,b.weight,b.type,a.quantity FROM recipe_ingredients a INNER JOIN food b ON a.id_food = b.id WHERE a.id_recipe = ?',[
                    $recipe['id']
                ]);

                $ingredientsArray = array();
                foreach ($ingredients as $ingredient){
                    $ingredientsArray[] = new Food($ingredient);
                }


                $return[] = new Recipe([
                    'id' => $recipe['id'],
                    'name' => $recipe['name'],
                    'ingredients' => $ingredientsArray
                ]);
            }

            return $return;
        }else{
            return null;
        }
    }
}