<hr>
<br>
<div class="container-fluid txt-container">
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
        require_once __DIR__ . "/../../repositories/FoodTruckRepository.php";

        $truckService = new FoodTruckRepository();

        $trucks = $truckService->getAll();

        foreach ($trucks

        as $truck){
        ?>

        <tr>
            <td><?= $truck->getId(); ?></td>

            <td><?= $truck->getDateRegister() ?></td>

            <td><?= $truck->getDateCheck() ?></td>

            <td><?= $truck->getMileage() ?></td>
<?php
} ?>

</tbody>
</table>
</div>


