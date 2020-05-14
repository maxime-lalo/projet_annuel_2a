<?php
require_once __DIR__ . "/../../repositories/UserRepository.php";
$uRepository = new UserRepository();

if ( isset($_GET['id']) && isset($_GET['type']) ){
    if ($_GET['type'] == "accept" OR $_GET['type'] == "refuse"){
        $update = $uRepository->processWorker($_GET['id'],$_GET['type']);
        if ($update){
            new SweetAlert("success","Succès","L'utilisateur a bien été mis à jour");
        }else{
            new SweetAlert("error","Erreur","Erreur lors de la mise à jour de l'utilisateur");
        }
    }
}
?>
<div class="container">
    <h1 id="page-title">
        <?= translate("Gestion des franchisés");?>
        <span class="float-right">
            <a href="#" class="btn btn-primary mb-2">
                <i class="fa fa-eye"></i>
                <?= translate("Voir tous les franchisés");?>
            </a>
        </span>
    </h1>
    <p class="lead"><?= translate("Liste des franchisés en attente");?></p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?= translate("ID");?></th>
            <th><?= translate("Nom");?></th>
            <th><?= translate("Email");?></th>
            <th><?= translate("Téléphone");?></th>
            <th><?= translate("Ville");?></th>
            <th><?= translate("CV");?></th>
            <th><?= translate("Actions");?></th>
        </tr>
        </thead>
        <tbody>
            <?php
            foreach ($uRepository->getNotActivatedWorkers() as $user){
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
                        <td>
                            <a class="btn btn-success" href="?id=<?= $user->getId();?>&type=accept">
                                <i class="fas fa-check"></i>
                            </a>
                            <a class="btn btn-danger" href="?id=<?= $user->getId();?>&type=refuse">
                                <i class="fas fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            $users = $uRepository->getNotActivatedWorkers();
            if ($users != null){
            }else{
                ?>
                <tr style="background-color:lightgray">
                    <td colspan="7" class="text-center"><?= translate("Aucun franchisé en attente");?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</div>