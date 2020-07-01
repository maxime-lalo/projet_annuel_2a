<?php
require_once __DIR__ . "/../../../repositories/EventRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
require_once __DIR__ . "/../../../repositories/ClientOrderRepository.php";

$eRepo = new EventRepository();
$uRepo = new UserRepository();
$cORepo = new ClientOrderRepository();
?>
<title><?= translate("Espace franchisé");?> - <?= translate("Gérer mes évènements");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace franchisé");?> - <?= translate("Gérer mes évènements");?>
        <span class="float-right">
            <a href="create" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> <?= translate("Créer un évènement");?></a>
        </span>
    </h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= translate("Nom de l'évènement");?></th>
                <th><?= translate("Date");?></th>
                <th><?= translate("Lieu");?></th>
                <th><?= translate("Type");?></th>
                <th><?= translate("Actions");?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $events = $eRepo->getFranchiseeEvents($user);
            if ($events != null){
                foreach($events as $event){
                    ?>
                    <tr>
                        <td><?= $event->getName();?></td>
                        <td><?= $event->getDate()->format("d/m/Y H:i");?></td>
                        <td><?= $event->getPlace();?></td>
                        <td><?= $eRepo->getTypeString($event->getType());?></td>
                        <td>
                            <button onclick="viewEvent(<?= $event->getId();?>)" class="btn btn-primary" data-toggle="tooltip" title="<?= translate('Voir les participants');?>"><i class="fa fa-eye"></i></button>
                            <?php
                            if ($event->getDate() > new DateTime('Now')){
                                $titleDelete = translate("Supprimer l'évènement");
                                ?>
                                <button class="btn btn-success" data-toggle="tooltip" title="<?= translate('Inviter un client');?>"><i class="fa fa-plus"></i></button>
                                <button onclick="deleteEvent(<?= $event->getId();?>)" class="btn btn-danger" data-toggle="tooltip" title="<?= $titleDelete;?>"><i class="fa fa-trash-alt"></i></button>
                                <?php
                            }
                            ?>

                        </td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr>
                    <td colspan="4"><?= translate("Aucun évènement planifié");?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function viewEvent(idEvent){
        $.get({
            url: '/api/event',
            data: {
                id: idEvent
            },
            dataType: "JSON",
            success: function(data){
                if (data.status === "success"){
                    var usersParticipating = data.Event.participants;
                    var usersInvited = data.Event.invited;
                    var html =
                        "<table class='table table-bordered'>" +
                            "<thead>" +
                                "<tr>" +
                                    "<th>Nom</th>" +
                                    "<th>État</th>" +
                                "</tr>" +
                            "</thead>" +
                            "<tbody>"
                    ;

                    if (usersParticipating.length <= 0 && usersInvited.length <= 0){
                        html += "<tr>" +
                                    "<td colspan='2'>Pas encore de participants</td>" +
                                "</tr>";
                    }else{
                        for (var i = 0; i < usersParticipating.length; i++){
                            html +=
                                "<tr>" +
                                    "<td>" + usersParticipating[i].firstname + " " + usersParticipating[i].lastname + "</td>" +
                                    "<td>Participant</td>" +
                                "</tr>"
                            ;
                        }

                        for (var j = 0; j < usersInvited.length; j++){
                            html +=
                                "<tr>" +
                                    "<td>" + usersInvited[j].firstname + " " + usersInvited[j].lastname + "</td>" +
                                    "<td>Invité</td>" +
                                "</tr>"
                            ;
                        }
                    }

                    html +=
                        "</tbody>" +
                    "</table>"
                    ;

                    Swal.fire({
                        title: "Participants à : " + data.Event.name,
                        html: html
                    })
                }else{
                    Swal.fire(
                        'Erreur',
                        "L'évènement n'a pas été trouvé",
                        'error'
                    )
                }
            }
        })


    }

    function deleteEvent(idEvent){
        Swal.fire({
            title: '<?= translate("Êtes-vous sûr ?");?>',
            text: '<?= translate("Vous ne pourrez pas revenir en arrière");?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#0205a1',
            confirmButtonText: "<?= translate('Supprimer');?>",
            cancelButtonText: "<?= translate('Annuler');?>",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : '/api/event',
                    type: 'DELETE',
                    data: 'id=' + idEvent
                }).done(function(data){
                    Swal.fire({
                        title: "<?= translate('Supprimé');?>",
                        text:"<?= translate('Cet évènement a bien été supprimé');?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location.reload(true);
                    })
                });

            }
        })
    }

    function inviteClient(email){

    }
</script>
