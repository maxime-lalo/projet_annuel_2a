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
    $uRep = new UserRepository();
    $order = $coRep->getOneById($json['id']);
    $user = $order->getUser();
    $userPoints = $user->getPoints();
    $user->setPoints($userPoints+$order->getTotalPrice());
    $uRep->update($user);
    $order->setIsPayed(1);
    $order->setStatus(0);
    $coRep->update($order);
    new JsonReturn(JsonReturn::SUCCESS,"Order Created",201, $order);
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}