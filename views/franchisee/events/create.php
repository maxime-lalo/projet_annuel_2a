<?php
require_once __DIR__ . "/../../../repositories/EventRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";

$eRepo = new EventRepository();
$uRepo = new UserRepository();
?>
<style>
    .row{
        margin-bottom: 10px;
    }
</style>
<title><?= translate("Espace franchisé");?> - <?= translate("Créer un évènement");?></title>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 id="page-title">
                <?= translate("Espace franchisé");?> - <?= translate("Créer un évènement");?>
                <span class="float-right">
            <a href="index" class="btn btn-primary mb-2"><i class="fa fa-history"></i> <?= translate("Liste de mes évènement");?></a>
        </span>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form id="addEvent">
                <div class="row">
                    <div class="col">
                        <label for="eventName"><?= translate("Nom");?></label>
                        <input type="text" class="form-control" name="eventName" id="eventName">
                    </div>
                    <div class="col">
                        <label for="eventName"><?= translate("Lieu");?></label>
                        <input type="text" class="form-control" name="eventPlace" id="eventPlace">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="eventName"><?= translate("Date");?></label>
                        <input type="date" class="form-control" name="eventDate" id="eventDate">
                    </div>
                    <div class="col">
                        <label for="eventName"><?= translate("Heure");?></label>
                        <input type="time" class="form-control" name="eventHour" id="eventHour">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="eventClients"><?= translate("Inviter mes X clients les plus fidèles");?></label>
                        <input type="number" class="form-control" name="eventClients" id="eventClients" value="10">
                    </div>
                    <div class="col">
                        <label for="eventType"><?= translate("Type d'évènement");?></label>
                        <select name="eventType" id="eventType" class="form-control">
                            <?php
                            $possibleTypes = $eRepo->getAllTypes();
                            foreach($possibleTypes as $type){
                                ?>
                                <option value="<?= $type['id'];?>"><?= translate($type['type']);?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="franchisee" value="<?= $user->getId();?>">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary" onclick="createEventFranchisee()"><?= translate("Créer l'évènement");?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    function createEventFranchisee(){
        $.ajax({
            url : '/api/event',
            type: 'post',
            data: JSON.stringify(getFormData($('#addEvent'))),
            dataType: "json",
            contentType: "application/json"
        }).always(function(data){
            if (data.status === "success"){
                Swal.fire({
                    title: "Succès",
                    icon: "success",
                    text: "L'évènement  a bien été crée"
                }).then((result) => {
                    document.location = "index ";
                })
            }else{
               Swal.fire({
                   title: "Erreur",
                   icon: "error",
                   text: "Veuillez remplir tous les champs"
               })
            }
        });
    }
</script>
