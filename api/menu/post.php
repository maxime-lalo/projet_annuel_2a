<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($_POST['name']) &&
    isset($_POST['price']) &&
    isset($_POST['recipes']) ||
    isset($_POST['ingredients'])
) {

    $fRepo = new FoodRepository();
    $rRepo = new RecipeRepository();
    foreach($_POST['ingredients'] as $ingredient){
        $ig = $fRepo->getOneById($ingredient);
        if($ig != null){
            $array_ingredients[] = $ig;
        }
    }

    foreach($_POST['recipes'] as $recipe){
        $re = $rRepo->getOneById($recipe);
        if($re != null){
          $array_recipes[] = $re;
        }
    }


    $mRepo = new MenuRepository();

    if(isset($_POST['menu_exist'])){
        $menu = new Menu([
            "id" => $_POST['menu_exist'],
            "name" => $_POST['name'],
            "ingredients" => $array_ingredients,
            "recipes" => $array_recipes,
            "price" => $_POST['price'],
            "recipes_num" => $_POST['recipes_num'],
            "ingredients_num" => $_POST['ingredients_num']        
        ]);  

    $res = $mRepo->update($menu);
        
}
    else{
        $menu = new Menu([
            "id" => 0,
            "name" => $_POST['name'],
            "ingredients" => $array_ingredients,
            "recipes" => $array_recipes,
            "price" => $_POST['price'],
            "recipes_num" => $_POST['recipes_num'],
            "ingredients_num" => $_POST['ingredients_num']        
        ]);
    
    $res = $mRepo->add($menu);
        }


    if ($res){
        new JsonReturn(JsonReturn::SUCCESS,"Menu created",201,$menu);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Error creating Menu",400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
} ?>