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
        if ($key == "eventClients"){
            if (empty($parameter)){
                if ($parameter < 0){
                    new JsonReturn(JsonReturn::ERROR, "Field ".$key." empty",400);
                    die(400);
                }
            }
        }else{
            if (empty($parameter)){
                new JsonReturn(JsonReturn::ERROR, "Field ".$key." empty",400);
                die(400);
            }
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

    if ($res != null && $res >= 0){
        $event->setId($res);
    }

    if ($res == -1){
        new JsonReturn(JsonReturn::ERROR,"Error while sending mails but event has been created",201);
    }elseif($res == -2){
        new JsonReturn(JsonReturn::ERROR,"Error while sending mails and event not created",500);
    }else{
        if ($res){
            new JsonReturn(JsonReturn::SUCCESS,"Event created",201,$event);
        }else{
            new JsonReturn(JsonReturn::ERROR,"Error creating event",400);
        }
    }
}else{
    new JsonReturn(JsonReturn::ERROR,"Missing fields",400);
}