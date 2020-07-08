<?php
if (isset($_GET['id'])){
    $rRepo = new RecipeRepository();
    $recipe = $rRepo->getOneById($_GET['id']);
    if ($recipe != null){
        new JsonReturn(JsonReturn::SUCCESS,"Recipe found",200,$recipe);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Recipe not found",404);
    }
}
