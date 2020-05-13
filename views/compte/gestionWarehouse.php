<hr>
<br>
<div class="container-fluid txt-container">
    <table id="warehouseList" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="th-sm">Id</th>
            <th class="th-sm">Name</th>
            <th class="th-sm">Street Name</th>
            <th class="th-sm">Street Number</th>
            <th class="th-sm">City</th>
        </tr>
        </thead>
        <tbody>
        <?php
        require_once __DIR__ . "/../../repositories/WarehouseRepository.php";

        $warehouseService = new WarehouseRepository();

        $warehouses = $warehouseService->getAll();

        foreach ($warehouses

        as $warehouse){
        ?>

        <tr>
            <td><?= $warehouse->getId(); ?></td>

            <td><?= $warehouse->getName() ?></td>

            <td><?= $warehouse->getStreetName() ?></td>

            <td><?= $warehouse->getStreetNumber() ?></td>

            <td><?= $warehouse->getCity() ?></td>

<?php
} ?>

</tbody>
</table>
</div>


