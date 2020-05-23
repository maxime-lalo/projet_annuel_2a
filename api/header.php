<?php
//header("Content-Type: application/json");

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
        $file_path = __DIR__ . "/" . $folder . "/post.php";
        break;
    default:
        $file_path = null;
        break;
}

if (file_exists($file_path)){
    $repos = array_diff(scandir(__DIR__ . "/../repositories"),array('.','..'));
    foreach ($repos as $repo){
        require_once(__DIR__ . "/../repositories/" . $repo);
    }
    require_once($file_path);
}else{
    echo json_encode(["status" => "error", "error" => "api not found"]);
}