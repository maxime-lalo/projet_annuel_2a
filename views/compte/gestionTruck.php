<div class="container-fluid txt-container">
    <?php
    require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";
    require_once __DIR__ . "/../../repositories/UserRepository.php";

    $userService = new UserRepository();
    $truckService = new FoodTruckRepository();

    if (isset($_POST['submit']) && isset($_POST['worker'])) {

        $value = ($_POST['worker']);
        $userService->setTruckFromUser($value, $_GET['id']);
    }

    if (!isset($_GET['id'])) {
        $trucks = $truckService->getAll(); ?>
        <table id="truckList" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Id</th>
                    <th class="th-sm">Date Register</th>
                    <th class="th-sm">Date last Check</th>
                    <th class="th-sm">Mileage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($trucks

                    as $truck) {
                ?>

                    <tr>
                        <td><?= $truck->getId(); ?></td>

                        <td><a href="/<?= LANG; ?>/compte/gestionTruck?id=<?= $truck->getId(); ?>"><?= $truck->getDateRegister() ?></a></td>

                        <td><?= $truck->getDateCheck() ?></td>

                        <td><?= $truck->getMileage() ?></td>
                    <?php
                } ?></tbody>
        </table> <?php
                } else {
                    $truck = $truckService->getOneById($_GET['id']);
                    $user = $userService->getFromTruckId($_GET['id']);
                    $users = $userService->getAll();
                    ?>
        <div class="tab-pane" id="camions">
            <form class="form" action="gestionTruck?id=<?= $truck->getId(); ?>" method="post">
                <h4>Gestion du Camion</h4>
                <hr>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label for="id">
                            <h4>Id</h4>
                        </label>
                        <input required disabled type="text" class="form-control" name="id" value="<?= $truck->getId(); ?>">
                    </div>
                    <div class="col-xs-6">
                        <label for="mileage">
                            <h4>Mileage</h4>
                        </label>
                        <input disabled required type="number" class="form-control" name="mileage" value="<?= $truck->getMileage(); ?>">
                    </div>
                    <div class="col-xs-6">
                        <label for="register">
                            <h4>Date Register</h4>
                        </label>
                        <input disabled required type="date" class="form-control" name="register" value="<?= $truck->getDateRegister(); ?>">
                    </div>

                    <div class="col-xs-6">
                        <label for="lastcheck">
                            <h4>Date last check</h4>
                        </label>
                        <input disabled required type="date" class="form-control" name="lastcheck" value="<?= $truck->getDateCheck(); ?>">
                    </div>

                    <?php if (isset($user)) { ?>


                        <div class="col-xs-6">
                            <label for="nameWorker">
                                <h4>Name Worker</h4>
                            </label>
                            <input required disabled type="text" class="form-control" name="nameWorker" value="<?= $user->getFirstname()  . ' ' . $user->getLastName(); ?>">
                        </div>

                        <div class="col-xs-6">
                            <label for="localisation">
                                <h4>Localisation</h4>
                            </label>
                            <input required disabled type="text" class="form-control" name="localisation" value="<?= $user->getCity(); ?>">
                        </div>

                    <?php } else {  ?>
                        <div class="col-xs-6">
                            <label for="worker">
                                <h4>Choose Worker</h4>
                            </label>
                            <select class="form-control" id="worker" name="worker">
                                <option value="0">--Select Worker--</option>
                                <?php foreach ($users as $us) { ?>
                                    <option value="<?= $us->getId(); ?>"><?= $us->getFirstname() . ' ' . $us->getLastname() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-12">
                            <br>
                            <button class="btn btn-lg btn-success" type="submit" name="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save </button>
                            <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i>
                                Reset
                            </button>
                        </div>

                    <?php } ?>

                </div>

            <?php
                }
            ?>

        </div>