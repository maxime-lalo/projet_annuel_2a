<?php
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";

$rRepo = new RecipeRepository();
if (isset($_POST['recipeName'])){
    $recipe = $rRepo->create($_POST['recipeName']);
    ?>
    <script>
        window.location = "edit?id=<?= $recipe;?>";
    </script>
    <?php
}
?>
<title><?= translate("Ajouter une recette");?></title>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 id="page-title"><?= translate("Ajouter une recette");?></h1>
            <form method="POST">
                <input type="text" name="recipeName" class="form-control mb-4" placeholder="Nom de la recette">
                <button class="btn btn-primary"><?= translate("Ajouter");?></button>
            </form>
        </div>
    </div>
</div>

