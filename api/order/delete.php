<?php

$json = json_decode(file_get_contents('php://input'), true);

if (isset($json['id']) && isset($json['user_type'])){
    if($json['user_type'] == 'client'){
        $coRepo = new ClientOrderRepository();
        $order = $coRepo->getOneById($json['id']);
        if($order->getStatus() == 0 || $order->getStatus() == 4){
            $order->setStatus(2);
            if($order->getUsePoints() == 1){
                $uRepo = new UserRepository();
                $user = $order->getUser();
                $userPoints = $user->getPoints();
                $user->setPoints($userPoints+($order->getTotalPrice()*2));
                $uRepo->update($user);
            }
            $coRepo->update($order);
            new JsonReturn(JsonReturn::SUCCESS,"Order cancelled",200,$order);
        }else{
            new JsonReturn(JsonReturn::ERROR,"Order can no longer be cancelled",401);
        }
    }elseif($json['user_type'] == 'franchisee'){
        new JsonReturn(JsonReturn::ERROR,"Not coded lol",410);
    }else{
        new JsonReturn(JsonReturn::ERROR,"User type not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}