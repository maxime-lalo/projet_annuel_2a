<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../repositories/EventRepository.php";
require_once __DIR__ . "/../repositories/UserRepository.php";

$transport = (new Swift_SmtpTransport('ssl://smtp.gmail.com', 465))
    ->setUsername('eplp.lloret@gmail.com')
    ->setPassword('ac5aabba1e')
;

$mailer = new Swift_Mailer($transport);

$uRepo = new UserRepository();
$eRepo = new EventRepository();

$allClients = $uRepo->getAllBy("is_client",1);

if ($allClients){

    $htmlMail = "<style>
        table, tr, td, th{
            border: 1px solid black;
        }
        td, th{
            padding: 5px 20px 5px 20px
        }
    </style>";
    /* @var $client User */
    foreach ($allClients as $client){
        $htmlMail .=
            "<h1>Bonjour, " .$client->getFirstname() . ".</h1>".
            "<p>Voici vos évènements à venir</p>".
            "<table>".
                "<thead>".
                    "<tr>".
                        "<th>Nom</th>".
                        "<th>Date</th>".
                        "<th>Lieu</th>".
                        "<th>Inscrit ?</th>".
                    "</tr>".
                "</thead>".
            "<tbody>"
        ;

        $clientEvents = $eRepo->getFollowingEvents($client);

        /* @var $event Event */
        foreach($clientEvents as $event){
            $strParticipates = $eRepo->isUserParticipating($client,$event) ? "Inscrit":"Non inscrit";

            $htmlMail .=
                "<tr>".
                    "<td>".$event->getName()."</td>".
                    "<td>".$event->getDate()->format("d/m/Y H:i")."</td>".
                    "<td>".$event->getPlace()."</td>".
                    "<td>".$strParticipates."</td>".
                "</tr>"
            ;
        }

        $htmlMail .=
                "</tbody>".
            "</table>".
            "<br>".
            "<a href='http://ffw-pmv.com/client/degustation'>Consulter mes évènements</a>"
        ;

        $message = (new Swift_Message('Newsletter DrivNCook'))
            ->setFrom(['drivncook@gmail.com' => 'DrivNCook'])
            ->setTo([$client->getEmail()])
            ->setBody($htmlMail)
        ;

        $message->setContentType("text/html");

        $result = $mailer->send($message);
    }
}