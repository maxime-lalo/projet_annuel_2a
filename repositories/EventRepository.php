<?php
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/../models/Event.php";
class EventRepository extends AbstractRepository
{

    const DEGUSTATION = 1;

    public function getOneById(int $id){
        $event = $this->dbManager->find("SELECT * FROM event WHERE id = ?" ,[
            $id
        ]);

        if ($event == null){
            return null;
        }else{
            $event['participants'] = $this->dbManager->getAll("SELECT id_user FROM event_user WHERE id_event = ?",[
                $event['id']
            ]);
            $strClass = $this->getClassName();
            return new $strClass($event);
        }
    }

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

    public function delete(int $id): bool
    {
        $res = parent::delete($id);
        $res2 = $this->dbManager->exec("DELETE FROM event_user WHERE id_event = ?",[
            $id
        ]);

        return $res AND $res2;
    }

    public function getTypeString(int $type):string{
        switch ($type){
            case 1:
                return translate("Dégustation");
            default:
                return translate("Non trouvé");
        }
    }

    public function add(Event $event, int $numberOfClients){
        $row = $this->dbManager->exec("INSERT INTO event (name, date, type, place, franchisee) VALUES (?,?,?,?,?)",[
            $event->getName(),
            $event->getDate()->format("Y-m-d H:i:s"),
            $event->getType(),
            $event->getPlace(),
            $event->getFranchisee()->getId()
        ]);

        if ($row == 1){
            return $this->dbManager->getLastInsertId();
        }
    }
}