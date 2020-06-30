<?php
//header("Content-Type: application/json");
require_once __DIR__ . "/Parameters.php";
require_once __DIR__ . "/JsonReturn.php";
$p = new Parameters();

// On inclue tous les repos
$repos = array_diff(scandir(__DIR__ . "/../repositories"),array('.','..'));
foreach ($repos as $repo){
    require_once(__DIR__ . "/../repositories/" . $repo);
}

// En fonction de la méthode de la requête
$request_type = $_SERVER['REQUEST_METHOD'];
$folder = isset($explodedUrl[1]) ? $explodedUrl[1]:null;
switch ($request_type){
    case "GET":
        $file_path = __DIR__ . "/" . $folder . "/get.php";
        break;
    case "PUT":
        $file_path = __DIR__ . "/" . $folder . "/put.php";
        break;
    case "DELETE":
        $file_path = __DIR__ . "/" . $folder . "/delete.php";
        break;
    case "POST":
        require_once(__DIR__ . "/../services/auth/AuthService.php");
        $file_path = __DIR__ . "/" . $folder . "/post.php";
        break;
    default:
        $file_path = null;
        break;
}

if (file_exists($file_path)){
    require_once($file_path);
}else{
    echo json_encode(["status" => "error", "error" => "api not found"]);
}