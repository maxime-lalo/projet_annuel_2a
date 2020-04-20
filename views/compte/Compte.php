<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';

$manager = new DatabaseManager();
$authService = new AuthService($manager);
$user_id = $_COOKIE["user_id"];

if (isset($_POST['submit']) && isset($_POST['firstname']) && isset($_POST['lastname'])
    && isset($_POST['email']) && isset($_POST['phone'])
    && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['number'])) {
    $user = $authService->updateUser($_POST['firstname'], $_POST['lastname'], $_POST['email'],
        $_POST['phone'], $_POST['address'], $_POST['number'], $_POST['city'], $user_id);
} else {
    $user = $authService->getUserFromId($user_id);
}
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<hr>
<br>
<div class="container-fluid txt-container">
    <div class="container bootstrap snippet">
        <div class="row">
            <div class="col-sm-10"><h1><?= $user->getFirstname() . ' ' . $user->getLastname(); ?></h1></div>
            <div class="col-sm-2"><a href="#" class="pull-right"><img title="profile image"
                                                                      class="img-circle img-responsive"
                                                                      src="http://www.gravatar.com/avatar/28fd20ccec6865e2d5f0e1f4446eb7bf?s=100"></a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"><!--left col-->


                <div class="text-center">
                    <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail"
                         alt="avatar">
                    <h6>Upload a different photo...</h6>
                    <input type="file" class="text-center center-block file-upload">
                </div>
                </hr><br>


                <div class="panel panel-default">
                    <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body"><a href="http://bootnipets.com">bootnipets.com</a></div>
                </div>


                <ul class="list-group">
                    <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Shares</strong></span> 125
                    </li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Likes</strong></span> 13</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Posts</strong></span> 37</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Followers</strong></span> 78
                    </li>
                </ul>

                <div class="panel panel-default">
                    <div class="panel-heading">Social Media</div>
                    <div class="panel-body">
                        <i class="fa fa-facebook fa-2x"></i> <i class="fa fa-github fa-2x"></i> <i
                                class="fa fa-twitter fa-2x"></i> <i class="fa fa-pinterest fa-2x"></i> <i
                                class="fa fa-google-plus fa-2x"></i>
                    </div>
                </div>

            </div><!--/col-3-->
            <div class="col-sm-9">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                    <li><a data-toggle="tab" href="#messages">Menu 1</a></li>
                    <li><a data-toggle="tab" href="#settings">Menu 2</a></li>
                </ul>


                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <hr>
                        <form class="form" action="Compte" method="post"
                              id="registrationForm">
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="firstname"><h4>First name</h4></label>
                                    <input required type="text" class="form-control" name="firstname"
                                           value="<?= $user->getFirstname(); ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="lastname"><h4>Last name</h4></label>
                                    <input required type="text" class="form-control" name="lastname"
                                           value="<?= $user->getLastname(); ?>">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="phone"><h4>Phone</h4></label>
                                    <input required type="tel" class="form-control" name="phone"
                                           pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$"
                                           value="<?= $user->getPhone(); ?>">
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <label for="email"><h4>Email</h4></label>
                                <input required type="email" class="form-control" name="email"
                                       value="<?= $user->getEmail(); ?>">
                            </div>

                            <div class="col-xs-6">
                                <label for="address"><h4>Rue</h4></label>
                                <input required type="text" class="form-control" name="address"
                                       value="<?= $user->getStreetName(); ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="number"><h4>Numéro</h4></label>
                                <input required type="number" class="form-control" name="number"
                                       value="<?= $user->getStreetNumber(); ?>">
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="city"><h4>Ville</h4></label>
                                    <input required type="text" class="form-control" name="city"
                                           value="<?= $user->getCity(); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-lg btn-success" type="submit" name="submit"><i
                                                class="glyphicon glyphicon-ok-sign"></i> Save
                                    </button>
                                    <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr>

                    </div><!--/tab-pane-->
                    <div class="tab-pane" id="messages">

                    </div><!--/tab-pane-->
                    <div class="tab-pane" id="settings">
                    </div>

                </div><!--/tab-pane-->
            </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
    <script>
        $(document).ready(function () {
            var readURL = function (input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.avatar').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }


            $(".file-upload").on('change', function () {
                readURL(this);
            });
        });
    </script>
