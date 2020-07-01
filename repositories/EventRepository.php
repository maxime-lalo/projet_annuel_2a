<?php
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/../models/Event.php";
class EventRepository extends AbstractRepository
{

    const DEGUSTATION = 1;

    public function getUserEvents(User $user,int $type){
        $rows = $this->dbManager->getAll("SELECT b.id,b.name,b.date,b.type,b.place,b.franchisee FROM event_user a INNER JOIN event b ON a.id_event = b.id WHERE a.id_user = ? AND b.type = ? ORDER BY date DESC",[
            $user->getId(),
            $type
        ]);

        if ($rows != null){
            $return = [];
            foreach($rows as $event){
                $return[] = new Event($event);
            }
            return $return;
        }else{
            return null;
        }
    }

    public function isUserParticipating(User $user, Event $event):?bool{
        $row = $this->dbManager->find("SELECT state FROM event_user WHERE id_user = ? AND id_event = ?",[
            $user->getId(),
            $event->getId()
        ]);

        return $row != null ? $row['state']:null;
    }

    public function eventAction(User $user, Event $event, string $action):bool{
        $state = $action == "join" ? 1:0;
        $rows = $this->dbManager->exec("UPDATE event_user SET state = ? WHERE id_event = ? AND id_user = ?",[
            $state,
            $event->getId(),
            $user->getId()
        ]);

        return $rows == 1;
    }

    public function getFranchiseeEvents(User $franchisee){
        $rows = $this->dbManager->getAll("SELECT * FROM event WHERE franchisee = ? ORDER BY date DESC",[
            $franchisee->getId(),
        ]);

        if ($rows != null){
            $return = [];
            foreach($rows as $event){
                $return[] = new Event($event);
            }
            return $return;
        }else{
            return null;
        }
    }
}