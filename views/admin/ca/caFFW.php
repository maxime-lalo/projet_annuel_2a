<?php

require_once __DIR__ . "/../../../repositories/CaRepository.php";
$CaRepo = new CaRepository();
if(isset($_GET['dateBegin']) && isset($_GET['dateEnd'])) {
    $dateBegin = date($_GET['dateBegin']);
    $dateEnd = date($_GET['dateEnd']);
}
else{
    $dateBegin =  date('Y-m-d\TH:i:s', time());
    $dateEnd =  date('Y-m-d\TH:i:s', time());

}

$CA = $CaRepo->getByDate($dateBegin,$dateEnd);
$total_price = 0;
$total_price_ca = 0;

?>
<title><?= translate("Espace Admin");?> - <?= translate("Gestion du chiffre d'affaire");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Gestion du chiffre d'affaire");?>

    </h1>
    <p class="lead">
        <a href="generatePdf?pdf=true&dateBegin=<?= $dateBegin;?>&dateEnd=<?=$dateEnd;?>" class="btn btn-primary ml-2" target="blank"><i class="fas fa-print"></i> <?= translate("Imprimer le ca");?></a>

    </p>

    <label>
        <input value="<?=$dateBegin;?>" id="date_begin" type="datetime-local">
    </label> <label>
        <input value="<?=$dateEnd;?>" id="date_end" type="datetime-local">
    </label>
        <button class="btn btn-primary" onclick="loadCa($('#date_begin').val(), $('#date_end').val())">Actualiser</button>


    <br>
    <br>

    <?php if($CA){ ?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th><?= translate("ID");?></th>
        <th><?= translate("User");?></th>
        <th><?= translate("Date");?></th>
        <th><?= translate("Prix de base");?></th>
        <th><?= translate("CA");?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($CA as $ca){

        $total_price += $ca->getPrice();
        $total_price_ca += $ca->getPriceCa();
        ?>
        <tr>
    <td><?=$ca->getId()?></td>
    <td><?=$ca->getIdUser()?></td>
    <td><?=$ca->getDate()->format('d/m/Y');?></td>
    <td><?=$ca->getPrice()?> €</td>
    <td>+<?=$ca->getPriceCa()?> €</td>
        </tr>

    <?php }  ?>
    <tr style="background-color: #54F055;font-size: larger;font-weight: bold">
        <td>TOTAL</td>
        <td>TOTAL</td>
        <td>TOTAL</td>
        <td><?=$total_price?> €</td>
        <td>+<?=$total_price_ca?> €</td>
    </tr>


    </tbody>

</table>
<?php }
else{
?>
    <a>Pas de chiffre pour les dates sélectionnées </a>
    <?php } ?>

    <script type="text/javascript">
        function loadCa(dateBegin , dateEnd){
            Swal.fire({
                title: '<?= translate("Actualisation du Ca ?");?>',
                text: '<?= translate("Vous aller actualiser pour les dates : ");?>' + dateBegin + " à " + dateEnd,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#0205a1',
                confirmButtonText: "<?= translate('Actualiser');?>",
                cancelButtonText: "<?= translate('Annuler');?>",
            }).then((result) => {
                window.location.search = 'dateBegin=' + dateBegin.replace("T", "\\T") + '&dateEnd=' + dateEnd.replace("T", "\\T");
            })
        }
    </script>