<?php
require_once __DIR__ . "/../../repositories/UserRepository.php";
$uRepository = new UserRepository();
?>
<title><?= translate("Tous les franchisés");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Tous les franchisés");?>
        <span class="float-right">
            <a href="manageFranchisees" class="btn btn-primary mb-2">
                <i class="fa fa-eye"></i>
                <?= translate("Voir les franchisés en attente");?>
            </a>
        </span>
    </h1>
    <p class="lead"><?= translate("Liste des franchisés");?></p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= translate("ID");?></th>
            <th><?= translate("Nom");?></th>
            <th><?= translate("Email");?></th>
            <th><?= translate("Téléphone");?></th>
            <th><?= translate("Ville");?></th>
            <th><?= translate("CV");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($uRepository->getAllWorkers() as $user){
            ?>
            <tr>
                <td><?= $user->getId();?></td>
                <td><?= $user->getFirstname() . " " . $user->getLastname() ;?></td>
                <td><?= $user->getEmail();?></td>
                <td><?= $user->getPhone();?></td>
                <td><?= $user->getCity();?></td>
                <td>
                    <?php

                    $fileName = array_diff(scandir(__DIR__ . "/../../uploads/resumees/" . str_replace("@","_",$user->getEmail())), array('.', '..'));
                    if (isset($fileName[2])){
                        ?>
                        <a class="btn btn-primary" href="/file?type=download&file=resumees/<?= str_replace("@","_",$user->getEmail())."/".$fileName[2];?>" target="blank_">
                            <i class="fa fa-eye"></i>
                        </a>
                        <?php
                    }else{
                        echo "CV non trouvé";
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