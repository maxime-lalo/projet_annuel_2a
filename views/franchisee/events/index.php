<?php
require_once __DIR__ . "/../../../repositories/EventRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";

$eRepo = new EventRepository();
$uRepo = new UserRepository();
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
                        <td>
                            <button class="btn btn-primary" data-toggle="tooltip" title="<?= translate('Voir les participants');?>"><i class="fa fa-eye"></i></button>
                            <?php
                            if ($event->getDate() > new DateTime('Now')){
                                $titleDelete = translate("Supprimer l'évènement");
                                ?>
                                <button class="btn btn-success" data-toggle="tooltip" title="<?= translate('Inviter un client');?>"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger" data-toggle="tooltip" title="<?= $titleDelete;?>"><i class="fa fa-trash-alt"></i></button>
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
