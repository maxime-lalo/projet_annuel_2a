<?php
require_once __DIR__ . "/../../repositories/UserRepository.php";
$uRepository = new UserRepository();
?>
<title><?= translate("Tous les franchisés");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Tous les franchisés");?>
        <span class="float-right">
        <?php
            if(isset($_GET['id'])){
                $users = array();
                $users[] = $uRepository->getOneById($_GET['id']);
                if($users[0] != null && $users[0]->isWorker()){
                    ?>
                        <a href="manageFranchisees" class="btn btn-primary mb-2">
                            <i class="fa fa-eye"></i>
                            <?= translate("Afficher le reste des franchisés");?>
                        </a>
                    <?php
                }else{
                    $users = $uRepository->getAllWorkers();
                }
            }else{
                $users = $uRepository->getAllWorkers();
            }
        ?>
            <a href="manageNewFranchisees" class="btn btn-primary mb-2">
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
            <th><?=translate("Actions");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $users as $user){
            ?>
            <tr>
                <td><?= $user->getId();?></td>
                <td><?= $user->getFirstname() . " " . $user->getLastname() ;?></td>
                <td><?= $user->getEmail();?></td>
                <td><?= $user->getPhone();?></td>
                <td><?= $user->getCity();?></td>
                <td>
                    <?php

                    $resumeePath = __DIR__ . "/../../uploads/resumees/" . str_replace("@","_",$user->getEmail());
                    if (file_exists($resumeePath)){
                        $fileName = array_diff(scandir($resumeePath), array('.', '..'));
                        if (isset($fileName[2])){
                            ?>
                            <a class="btn btn-primary" href="/file?type=download&file=resumees/<?= str_replace("@","_",$user->getEmail())."/".$fileName[2];?>" target="blank_">
                                <i class="fa fa-eye"></i>
                            </a>
                            <?php
                        }else{
                            echo translate("CV non trouvé");
                        }
                    }else{
                        echo translate("CV non trouvé");
                    }
                    ?>
                </td>
                <td id="actions<?= $user->getId();?>">
                    <button class="btn btn-danger" title="<?= translate("Supprimer");?>" data-toggle="tooltip" onclick="deleteUser(<?= $user->getId();?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-primary" title="<?= translate("Éditer");?>" data-toggle="tooltip" onclick="editUser(<?= $user->getId();?>)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <a class="btn btn-primary" title="<?= translate("Commande");?>" data-toggle="tooltip" href="#">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

</div>