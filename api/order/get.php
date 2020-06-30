<?php
if (isset($_GET['id']) && isset($_GET['user_type'])){
    if($_GET['user_type'] == 'client'){
        $coRepo = new ClientOrderRepository();
        $order = $coRepo->getOneById($_GET['id']);
        new JsonReturn(JsonReturn::SUCCESS,"Client order found",200,$order);

    }elseif($_GET['user_type'] == 'franchisee'){
        new JsonReturn(JsonReturn::ERROR,"Not coded lol",410);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User type not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}