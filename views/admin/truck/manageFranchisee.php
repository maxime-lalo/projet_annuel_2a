<?php
require_once __DIR__ . "/../../../repositories/FoodTruckRepository.php";
require_once __DIR__ . "/../../../repositories/UserRepository.php";
$tRepo = new FoodTruckRepository();
$uRepo = new UserRepository();

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
                        <input type="email" name="email" id="email" class="form-control" placeholder="<?= translate('Rentrez l\'email d\'un franchisé');?>"  onkeyup="showUsers()">
                        <div id="resetDiv"></div>
                        <div id="resultsDiv" class="dropdown" hidden="hidden">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?= translate("Franchisés")?>
                            </button>
                            <div id="results" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                        <p id="franchiseeName" class="mt-2 text-center"><?= $u->getLastname()." ".$u->getFirstname();?></p>
                        
                        
                        <input type="email" name="email" id="email" class="form-control" placeholder="<?= translate('Rentrez l\'email d\'un franchisé');?>"  onkeyup="showUsers()" value="<?= $u->getEmail();?>">
                        <div id="resetDiv">
                            <a class="btn btn-danger"  data-toggle="tooltip" onclick="resetUser()">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                        <div id="resultsDiv" class="dropdown" hidden="hidden">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?= translate("Franchisés")?>
                            </button>
                            <div id="results" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                </td>
            </tr>
        </table>
        <?php
    }else{
        echo translate("Veuillez sélectionner un camion valide");
    }
    ?>
</div>
<script type="text/javascript">
var currentUser = '<?=($u != null)? $u->getId(): "null";?>';

    function showUsers() {
        var search = $('#email')
        var currentTruck = <?= $truck->getId(); ?>;

        if(search.val() != ""){
            var link = encodeURI('/api/user?email_like='+search.val())
            var dropdown = $('#results')
            var dropDownDiv = $('#resultsDiv')

            $.ajax({
                url : link,
                type: 'GET'
            }).done(function(data){
                if(data.status !== "error"){
                    var links = ``
                    data.Users.forEach(function(user, index, arr){
                        if(user.is_worker == 1){
                            links += `<a class="dropdown-item" onclick="action('`+user.id+`')">`+user.email+` - `+user.firstname+` `+user.lastname+`</a>`
                        }
                    })
                    dropdown.html(links)
                }
                dropDownDiv.removeAttr("hidden")
            });
        }else{
            $('#resultsDiv').attr("hidden", "hidden")
            $('#results').html('')
        }
    }

    function action(id){
        if(currentUser != 'null'){
            resetUser(currentUser)
        }else{
            var resetDiv = '<a class="btn btn-danger" title="<?= translate("Franchisé");?>" data-toggle="tooltip" onclick="resetUser()"><i class="fas fa-user"></i></a>'
            $('#resetDiv').html(resetDiv);
        }
        getUser(id)
    }

    function checkUser(user){
        console.log(user)
        if(!user.truck){
            var truck = {
                id : <?= $truck->getId(); ?>
            }
            user.truck = truck
            $.ajax({
                url : '/api/user',
                type: 'PUT',
                data: JSON.stringify(user)
            }).always(function(data){
                if (data.status === "success"){
                    currentUser = user.id
                    var firstname = data.User.firstname;
                    var lastname = data.User.lastname;
                    $('#results').html('')
                    $('#resultsDiv').attr("hidden", "hidden");
                    $('#franchiseeName').html(lastname + " " + firstname);
                    $('#email').val(user.email);
                    Swal.fire({
                        title: '<?= translate("Succès");?>',
                        text: '<?= translate("Le camion a bien été mis à jour");?>',
                        icon: "success"
                    });
                }else{
                    Swal.fire({
                        title: '<?= translate("Erreur");?>',
                        text: '<?= translate("Une erreur est survenu !");?>',
                        icon: "error"
                    });
                }
            })
        }else{
            Swal.fire({
                title: '<?= translate("Erreur");?>',
                text: user.firstname+'<?= translate(" a déjà un camion attribué !");?>',
                icon: "error"
            });
        }   
    }

    function getUser(id){
        
        $.ajax({
            url : '/api/user?id='+id,
            type: 'GET',
        }).always(function(data){
            if (data.status === "success"){
                checkUser(data.User)
            }else{
                Swal.fire({
                    title: '<?= translate("Erreur");?>',
                    text: '<?= translate("Une erreur est survenu !");?>',
                    icon: "error"
                });
            }
        })
    }

    function resetUser(){
        $.ajax({
            url : '/api/user?id='+currentUser,
            type: 'GET',
        }).always(function(data){
            if (data.status === "success"){
                user = data.User
                user.truck = 'null'
                $.ajax({
                    url : '/api/user',
                    type: 'PUT',
                    data: JSON.stringify(user)
                }).always(function(data){
                    if (data.status != "success"){
                        Swal.fire({
                            title: '<?= translate("Erreur");?>',
                            text: '<?= translate("Une erreur est survenu !");?>',
                            icon: "error"
                        });
                    }
                    if(data.status == "success" ){
                        $('#resetDiv').html('');
                        $('#results').html('')
                        $('#franchiseeName').html('<?= translate("Aucun franchisé lié");?>');
                        $('#email').val('');
                    }
                })
            }else{
                Swal.fire({
                    title: '<?= translate("Erreur");?>',
                    text: '<?= translate("Une erreur est survenu !");?>',
                    icon: "error"
                });
            }
        })
    }
</script>