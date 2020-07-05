<?php
if (isset($_GET['id'])){
    $mRepo = new MenuRepository();
    $menu = $mRepo->getOneById($_GET['id']);
    if ($menu != null){
        new JsonReturn(JsonReturn::SUCCESS,"Menu found",200,$menu);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Menu not found",404);
    }
}