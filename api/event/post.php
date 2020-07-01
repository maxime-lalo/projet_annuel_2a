<?php
$json = json_decode(file_get_contents('php://input'), true);
if (
    isset($json['eventName']) &&
    isset($json['eventPlace']) &&
    isset($json['eventDate']) &&
    isset($json['eventHour']) &&
    isset($json['eventClients']) &&
    isset($json['eventType']) &&
    isset($json['franchisee'])
) {
    foreach($json as $key => $parameter){
        if (empty($parameter)){
            new JsonReturn(JsonReturn::ERROR, "Field ".$key." empty",400);
            break;
        }
    }

    $event = new Event([
        "id" => 0,
        "date" => $json['eventDate'] . " " . $json['eventHour'],
        "type" => $json['eventType'],
        "name" => $json['eventName'],
        "place" => $json['eventPlace'],
        "franchisee" => $json['franchisee']
    ]);
    $eRepo = new EventRepository();

    $res = $eRepo->add($event, $json['eventClients']);

    if ($res != null){
        $event->setId($res);
    }

    if ($res){
        new JsonReturn(JsonReturn::SUCCESS,"Event created",201,$event);
    }else{
        new JsonReturn(JsonReturn::ERROR,"Error creating event",400);
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
}