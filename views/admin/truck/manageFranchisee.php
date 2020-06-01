<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$tRepo = new FoodTruckRepository();
$uRepo = new UserRepository();

if (isset($_POST['email']) && isset($_POST['truck'])){
    $user = $uRepo->getOneByEmail($_POST['email']);
    $uRepo->setTruckFromUser($user->getId(),$_POST['truck']);
    new SweetAlert(SweetAlert::SUCCESS,"Succès","Le franchisé a bien été lié au camion");
}
?>
<title><?= translate("Espace Admin");?> - <?= translate("Franchisé lié à un camion");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Franchisé lié à un camion");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="gestionTruck">
                <i class="fas fa-undo-alt"></i> <?= translate("Gestion des camions");?>
            </a>
        </span>
    </h1>
    <?php
    if (isset($_GET['id'])){
        $truck = $tRepo->getOneById($_GET['id']);
        ?>
        <form method="POST">
            <table class="table table-bordered">
                <tr>
                    <th><?= translate("ID");?></th>
                    <td><?= $truck->getId();?></td>
                </tr>
                <tr>
                    <th><?= translate("Date d'enregistrement");?></th>
                    <td><?= timestampFormat($truck->getDateRegister());?></td>
                </tr>
                <tr>
                    <th><?= translate("Date du dernier checkup");?></th>
                    <td><?= timestampFormat($truck->getDateCheck());?></td>
                </tr>
                <tr>
                    <th><?= translate("Kilométrage");?></th>
                    <td><?= $truck->getMileage();?></td>
                </tr>
                <tr>
                    <th><?= translate("Marque");?></th>
                    <td><?= $truck->getBrand();?></td>
                </tr>
                <tr>
                    <th><?= translate("Modèle");?></th>
                    <td><?= $truck->getModel();?></td>
                </tr>
                <tr>
                    <th><?= translate("Franchisé");?></th>
                    <td>
                        <?php
                        $u = $tRepo->getUser($truck);
                        if ($u == null){
                            ?>
                            <p id="franchiseeName" class="mt-2 text-center"><?= translate("Aucun franchisé lié");?></p>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?= translate('Rentrez l\'email d\'un franchisé');?>" onchange="checkUser()" onkeyup="checkUser()">
                            <?php
                        }else{
                            ?>
                            <p id="franchiseeName" class="mt-2 text-center"><?= $u->getLastname()." ".$u->getFirstname();?></p>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?= translate('Rentrez l\'email d\'un franchisé');?>" onchange="checkUser()" onkeyup="checkUser()" value="<?= $u->getEmail();?>">
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?= $truck->getId();?>" name="truck">
            <button class="btn btn-primary" id="btnValidate" onclick="changeFranchisee()" disabled>
                <?= translate("Enregistrer les modifications");?>
            </button>
        </form>
        <?php
    }else{
        echo translate("Veuillez sélectionner un camion valide");
    }
    ?>
</div>
<script type="text/javascript">
    function checkUser(){
        var email = $('#email').val();
        $.ajax({
            url : '/api/user?email=' + email,
            type: 'GET',
        }).always(function(data){
            if (data.status === "success"){
                var firstname = data.User.firstname;
                var lastname = data.User.lastname;
                if (data.User.is_worker == 1){
                    $('#franchiseeName').html(lastname + " " + firstname);
                    $('#btnValidate').attr('disabled',false);
                }else{
                    $('#franchiseeName').html('<?= translate("Utilisateur non franchisé");?>');
                    $('#btnValidate').attr('disabled',true);
                }
            }else{
                $('#franchiseeName').html('<?= translate("Utilisateur non trouvé");?>');
                $('#btnValidate').attr('disabled',true);
            }
        })
    }

    function changeFranchisee(){
        var email = $('#email').val();

    }
</script>