<?php
require_once __DIR__ . "/../../repositories/EventRepository.php";
$eRepo = new EventRepository();

if (isset($_POST['idEvent']) && isset($_POST['action'])){
    $res = $eRepo->eventAction($user,$eRepo->getOneById($_POST['idEvent']),$_POST['action']);
    if ($res){
        if ($_POST['action'] == 'join'){
            new SweetAlert(SweetAlert::SUCCESS,"Succès","Vous vous êtes bien inscrit(e) à cet évènement");
        }else{
            new SweetAlert(SweetAlert::SUCCESS,"Succès","Vous vous êtes bien désinscrit(e) de cet évènement");
        }
    }else{
        new SweetAlert(SweetAlert::ERROR,"Erreur","Erreur de base de données");
    }
}
?>
<title><?= translate("Espace Client");?> - <?= translate("Mes évènements");?></title>
<div class="container">
    <h1 id="page-title"><?= translate("Espace Client");?> - <?= translate("Mes évènements");?></h1>
    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= translate("Id");?></th>
                    <th><?= translate("Nom");?></th>
                    <th><?= translate("Date");?></th>
                    <th><?= translate("Lieu");?></th>
                    <th><?= translate("Type");?></th>
                    <th><?= translate("Actions");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = new DateTime("Now");
                $events = $eRepo->getUserEvents($user,EventRepository::ALL_EVENTS);
                /* @var $event Event */
                foreach($events as $event){
                    ?>
                    <tr>
                        <td><?= $event->getId();?></td>
                        <td><?= $event->getName();?></td>
                        <td><?= $event->getDate()->format('d/m/Y H:i');?></td>
                        <td><?= $event->getPlace();?></td>
                        <td><?= $eRepo->getTypeString($event->getType());?></td>
                        <td>
                            <?php
                            if ($event->getDate() < $today){
                                echo translate("Évènement terminé");
                            }else{
                                if ($eRepo->isUserParticipating($user,$event)){
                                    ?>
                                    <button class="btn btn-danger" data-toggle="tooltip" title="<?= translate("Ne plus participer");?>" onclick="eventAction(<?= $event->getId();?>,'leave')"><i class="fas fa-sign-out-alt"></i></button>
                                    <?php
                                }else{
                                    ?>
                                    <button class="btn btn-success" data-toggle="tooltip" title="<?= translate("Participer");?>" onclick="eventAction(<?= $event->getId();?>,'join')"><i class="fas fa-sign-in-alt"></i></button>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function eventAction(idEvent, action) {
        var form = {
            idEvent: idEvent,
            action: action,
        };

        redirectPost('', form);
    }
</script>
