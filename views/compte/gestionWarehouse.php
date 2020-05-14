<hr>
<br>
<div class="container-fluid txt-container">
    
        <?php
        require_once __DIR__ . "/../../repositories/WarehouseRepository.php";

        $warehouseService = new WarehouseRepository();

        if(!isset($_GET['id'])){
            $warehouses = $warehouseService->getAll();
            ?>
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
        foreach ($warehouses

        as $warehouse){
        ?>

        <tr>
            <td><?= $warehouse->getId(); ?></a></td>

            <td><a href="/<?= LANG; ?>/compte/gestionWarehouse?id=<?=$warehouse->getId(); ?>"><?= $warehouse->getName() ?></a></td>

            <td><?= $warehouse->getStreetName() ?></td>

            <td><?= $warehouse->getStreetNumber() ?></td>

            <td><?= $warehouse->getCity() ?></td>

<?php
} ?>
</tbody>
</table>
<?php }
else{
    $warehouse = $warehouseService->getOneById($_GET['id']);
    ?>
    <div class="tab-pane" id="warehouse">
        <h4>Gestion de l'entrepôt</h4>
                  <hr>                        
                      <div class="form-group">
                      <div class="col-xs-6">
                              <label for="id"><h4>Id</h4></label>
                              <input required disabled type="text" class="form-control" name="id"
                                     value="<?= $warehouse->getId(); ?>">
                          </div>
                          <div class="col-xs-6">
                              <label for="name"><h4>Name</h4></label>
                              <input required type="text" class="form-control" name="name"
                                     value="<?= $warehouse->getName(); ?>">
                          </div>
                          <div class="col-xs-6">
                              <label for="street"><h4>Street Name</h4></label>
                              <input required  type="text" class="form-control" name="street"
                                     value="<?= $warehouse->getStreetName(); ?>">
                          </div>

                          <div class="col-xs-6">
                              <label for="number"><h4>Street Number</h4></label>
                              <input required  type="number" class="form-control" name="number"
                                     value="<?= $warehouse->getStreetNumber(); ?>">
                          </div>
                          <div class="col-xs-6">
                              <label for="city"><h4>City</h4></label>
                              <input required  type="text" class="form-control" name="city"
                                     value="<?= $warehouse->getCity(); ?>">
                          </div>
                      </div>

                      
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
  }
?>
</div>


