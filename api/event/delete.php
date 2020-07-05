<?php
if ($p->get('id') != null){
    $repo = new EventRepository();
    $delete = $repo->delete($p->get('id'));
    if ($delete){
        echo json_encode(["status" => "success", "msg" => "Successfully deleted"]);
    }else{
        echo json_encode(["status" => "error", "msg" => "Error during delete SQL"]);
    }
}else{
    echo json_encode(["status" => "error", "msg" => "missing id argument"]);
}