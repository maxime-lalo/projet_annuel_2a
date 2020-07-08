<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($_POST['id_userFrom']) &&
    isset($_POST['id_userTo']) &&
    isset($_POST['point'])
) {

    $uR = new UserRepository();
    $point = $_POST['point'];

    $client = $uR->getOneById($_POST['id_userFrom']);
    if($client != null) {
        $client->setPoints($client->getPoints() - $point);
        $clientTo = $uR->getOneById($_POST['id_userTo']);
        if($clientTo != null) {
            $clientTo->setPoints($clientTo->getPoints() + $point);
            $uR->update($client);
            $uR->update($clientTo);

            new JsonReturn(JsonReturn::SUCCESS, "Users updated", 201, $uR);
        }
    }
}
else{
    new JsonReturn(JsonReturn::ERROR,"Missing arguments",400);
}
