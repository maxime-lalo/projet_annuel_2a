<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../repositories/EventRepository.php";
require_once __DIR__ . "/../repositories/UserRepository.php";
require_once __DIR__ . "/Mailer.php";

$uRepo = new UserRepository();
$eRepo = new EventRepository();

$allClients = $uRepo->getAllBy("is_client",1);

if ($allClients){
    /* @var $client User */
    foreach ($allClients as $client){
        $htmlMail =
            "<h1>Bonjour, " .$client->getFirstname() . ".</h1>".
            "<p>Voici vos évènements à venir</p>".
            "<table style='border: 1px solid black'>".
                "<thead>".
                    "<tr style='border: 1px solid black'>".
                        "<th style='border: 1px solid black;padding: 5px 20px 5px 20px'>Nom</th>".
                        "<th style='border: 1px solid black;padding: 5px 20px 5px 20px'>Date</th>".
                        "<th style='border: 1px solid black;padding: 5px 20px 5px 20px'>Lieu</th>".
                        "<th style='border: 1px solid black;padding: 5px 20px 5px 20px'>Inscrit ?</th>".
                    "</tr>".
                "</thead>".
            "<tbody>"
        ;

        $clientEvents = $eRepo->getFollowingEvents($client);

        /* @var $event Event */
        foreach($clientEvents as $event){
            $strParticipates = $eRepo->isUserParticipating($client,$event) ? "Inscrit":"Non inscrit";

            $htmlMail .=
                "<tr style='border: 1px solid black'>".
                    "<td style='border: 1px solid black;padding: 5px 20px 5px 20px'>".$event->getName()."</td>".
                    "<td style='border: 1px solid black;padding: 5px 20px 5px 20px'>".$event->getDate()->format("d/m/Y H:i")."</td>".
                    "<td style='border: 1px solid black;padding: 5px 20px 5px 20px'>".$event->getPlace()."</td>".
                    "<td style='border: 1px solid black;padding: 5px 20px 5px 20px'>".$strParticipates."</td>".
                "</tr>"
            ;
        }

        $htmlMail .=
                "</tbody>".
            "</table>".
            "<br>".
            "<a href='http://ffw-pmv.com/client/events'>Consulter mes évènements</a>"
        ;

        Mailer::sendMail($client->getEmail(),"Newsletter DrivNCook",$htmlMail);

    }
}