<?php
require_once __DIR__ . "/../../repositories/UserRepository.php";
$uRepository = new UserRepository();
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
                    <td><button class="btn btn-primary"><i class="fa fa-eye"></i></button></td>
                    <td>
                        <button class="btn btn-success">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</div>
