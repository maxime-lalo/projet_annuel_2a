<?php
require_once __DIR__ . "/../../repositories/EventRepository.php";
$eRepo = new EventRepository();
?>
<title><?= translate("Espace Client");?> - <?= translate("Mes dégustations");?></title>
<div class="container">
    <h1 id="page-title"><?= translate("Espace Client");?> - <?= translate("Mes dégustations");?></h1>
    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= translate("Id");?></th>
                    <th><?= translate("Nom");?></th>
                    <th><?= translate("Date");?></th>
                    <th><?= translate("Actions");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $events = $eRepo->getAll();
                foreach($events as $event){
                    ?>
                    <tr>
                        <td><?= $event->getId();?></td>
                        <td><?= $event->getName();?></td>
                        <td><?= $event->getDate()->format('d/m/Y H:i');?></td>
                        <td></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
