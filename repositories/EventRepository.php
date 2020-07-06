<?php
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/ClientOrderRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/../models/Event.php";
class EventRepository extends AbstractRepository
{

    const DEGUSTATION = 1;
    const MEETING = 2;
    
    public function getOneById(int $id){
        $event = $this->dbManager->find("SELECT * FROM event WHERE id = ?" ,[
            $id
        ]);

        if ($event == null){
            return null;
        }else{
            $event['participants'] = $this->dbManager->getAll("SELECT id_user FROM event_user WHERE id_event = ? AND state = 1",[
                $event['id']
            ]);

            $event['invited'] = $this->dbManager->getAll("SELECT id_user FROM event_user WHERE id_event = ? AND state = 0",[
                $event['id']
            ]);
            return new Event($event);
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
        $row = $this->dbManager->find("SELECT type FROM event_type WHERE id = ?",[
            $type
        ]);

        return $row != null ? translate($row['type']):translate("Non trouvé");
    }

    public function getAllTypes():?array{
        $rows = $this->dbManager->getAll('SELECT * FROM event_type');
        return $rows ? $rows:null;
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
            $idEvent = $this->dbManager->getLastInsertId();

            $cORepo = new ClientOrderRepository();

            $clientsToInvite = $cORepo->getMostFaithfulClients($numberOfClients,$event->getFranchisee()->getTruck()->getId());

            foreach($clientsToInvite as $client){
                $row = $this->dbManager->exec("INSERT INTO event_user (id_event, id_user) VALUES (?,?)",[
                    $idEvent,
                    $client->getId()
                ]);
            }
            return $idEvent;
        }
    }

    public function inviteUser(Event $event,User $user){
        $row = $this->dbManager->find("SELECT * FROM event_user WHERE id_event = ? AND id_user = ?",[
            $event->getId(),
            $user->getId()
        ]);
        if ($row){
            return -1;
        }else{
            $invite = $this->dbManager->exec("INSERT INTO event_user (id_event, id_user) VALUES (?,?)",[
                $event->getId(),
                $user->getId()
            ]);

            return $invite ? true:false;
        }
    }

    public function getFollowingEvents(User $user)
    {
        $rows = $this->dbManager->getAll("SELECT b.id,b.name,b.date,b.type,b.place,b.franchisee FROM event_user a INNER JOIN event b ON a.id_event = b.id WHERE a.id_user = ? AND b.date >= CURDATE() ORDER BY date DESC",[
            $user->getId()
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