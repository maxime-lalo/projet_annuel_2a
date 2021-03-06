<?php
if (isset($_GET['id']) && isset($_GET['user_type'])){
    if($_GET['user_type'] == 'client'){
        $coRepo = new ClientOrderRepository();
        $order = $coRepo->getOneById($_GET['id']);
        new JsonReturn(JsonReturn::SUCCESS,"Client order found",200,$order);

    }elseif($_GET['user_type'] == 'worker'){
        new JsonReturn(JsonReturn::ERROR,"Not coded lol",410);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User type not found",404);
    }
}elseif(isset($_GET['user_id']) && isset($_GET['date_range']) && isset($_GET['user_type'])){
    if($_GET['user_type'] == 'worker'){
        $coRepo = new ClientOrderRepository();
        $orders = $coRepo->getAllFromDateAndFranchisee($_GET['user_id'], $_GET['date_range']);
        $result = array();
        $result += ["status" => "success"];
        $result  += ["info" => "Client orders returned"];
        $result += ["ClientOrders" => $orders];
        echo json_encode($result);
        http_response_code(200);

    }elseif($_GET['user_type'] == 'client'){
        new JsonReturn(JsonReturn::ERROR,"Not coded lol",410);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User type not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}