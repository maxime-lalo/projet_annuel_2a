<?php

$json = json_decode(file_get_contents('php://input'), true);

if (
    isset($json['id']) &&
    isset($json['card_number']) &&
    isset($json['card_month']) &&
    isset($json['card_year']) &&
    isset($json['user_id'])
) {
    $coRep = new ClientOrderRepository();
    $order = $coRep->getOneById($json['id']);
    if($order != null){
        $uRep = new UserRepository();
        $user = $order->getUser();
        $userPoints = $user->getPoints();
        $user->setPoints($userPoints+$order->getTotalPrice());
        $uRep->update($user);
        $order->setIsPayed(1);
        $order->setStatus(0);
        $coRep->update($order);
        new JsonReturn(JsonReturn::SUCCESS,"Status updated",200, $order);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Order not found",404);
    }
}elseif(isset($json['id']) && isset($json['new_status']) && isset($json['user_type']) && $json['user_type'] == "worker"){
    $foRep = new FranchiseeOrderRepository();
    $order = $foRep->getOneById($json['id']);
    if($order != null){
        if($json['new_status'] == 3){
            if($foRep->confirmOrder($order)){
                $order->setStatus(3);
                new JsonReturn(JsonReturn::SUCCESS,"Order Confirmed",200, $order);
            }else{
                new JsonReturn(JsonReturn::ERROR,"Could not update Order, try again.",304);
            }
        }else{
            new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
        }
    }else{
        new JsonReturn(JsonReturn::ERROR,"Order not found",404);
    }
}elseif(isset($json['id']) && isset($json['new_status'])){
    $coRep = new ClientOrderRepository();
    $order = $coRep->getOneById($json['id']);
    if($order != null){
        $order->setStatus($json['new_status']);
        $coRep->update($order);
        new JsonReturn(JsonReturn::SUCCESS,"Status updated",200, $order);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Order not found",404);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}