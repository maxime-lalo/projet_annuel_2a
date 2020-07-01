<?php
if (isset($_GET['id'])){
    $repo = new EventRepository();
    $item = $repo->getOneById($_GET['id']);
    if ($item != null){
        new JsonReturn(JsonReturn::SUCCESS,"Item found",200,$item);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Item not found",404);
    }
}
