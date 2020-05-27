<?php
if (isset($_GET['id'])){
    $uRepository = new UserRepository();
    echo json_encode($uRepository->getOneById($_GET['id']));
}else{
    echo json_encode(["status" => "error", "error" => "missing id argument in get"]);
}
