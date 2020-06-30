<?php
require_once __DIR__ . "/../../../repositories/MenuRepository.php";
require_once __DIR__ . "/../../../repositories/RecipeRepository.php";
require_once __DIR__ . "/../../../repositories/FoodRepository.php";
$menuRepo = new MenuRepository();
$recipeRepo = new RecipeRepository();
$foodRepo = new FoodRepository();
$name = "";
$price = 0;
if(isset($_GET['id'])){
    $id_menu = $_GET['id'];
    $menu = $menuRepo->getOneById($id_menu);
    if($menu != null) {
     foreach($menu->getRecipes() as $recipes){
        $recipes_inMenu[] = $recipes->getId();
        }
        foreach($menu->getIngredients() as $ingredient){
            $ingredients_inMenu[] = $ingredient->getId();
            }
        
        
        $name = $menu->getName();
        $price = $menu->getPrice();
    }

}

    $recipes = $recipeRepo->getAll();
    $ingredients = $foodRepo->getAll();

?>
<?php if(isset($menu)) {  ?>
<title><?= translate("Espace Admin");?> - <?= translate("Modifier un menu");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Modifier un menu");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="listofMenu">
                <i class="fa fa-edit"></i> <?= translate("Gestion des menus");?>
            </a>
        </span>
    </h1>
<?php } else { ?>
    <title><?= translate("Espace Admin");?> - <?= translate("Ajouter un menu");?></title>
<div class="container">
    <h1 id="page-title">
        <?= translate("Espace Admin");?> - <?= translate("Ajouter un menu");?>
        <span class="float-right">
            <a class="mb-2 btn btn-primary" href="listofMenu">
                <i class="fa fa-edit"></i> <?= translate("Gestion des menus");?>
            </a>
        </span>
    </h1>
<?php } ?>

    <form method="POST" id="formAdd">
        <div class="form-group">
            <label for="name"><?= translate("Nom du menu");?></label>
            <input type="text" class="form-control" name="name" id="name" value="<?=$name;?>" required>
<?php if(isset($menu)) {  ?>  <input type="text" class="form-control" style="visibility: hidden;" name="menu_exist" id="menu_exist" value="<?=$id_menu;?>" required> <?php } ?>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th><?= translate("Inclure ?");?></th>
                <th><?= translate("ID");?></th>
                <th><?= translate("Nom");?></th>
                <th><?= translate("Contenu");?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach($recipes as $recipe){
            $ingredients = $recipe->getIngredients();
            $last_key = end($ingredients);   ?>
            <tr id="row<?= $recipe->getId();?>">
            <?php if(isset($recipes_inMenu) && in_array($recipe->getId(), $recipes_inMenu)) {  ?><td><input type='checkbox' name='recipes[]' checked="true" value='<?=$recipe->getId();?>'></td> <?php } 
            else{ ?> <td><input type='checkbox' name='recipes[]' value='<?=$recipe->getId();?>'></td> <?php } ?>
                 <td> <?= $recipe->getId(); ?></td>
                 <td> <?= $recipe->getName(); ?></td>
                 <td>
               <?php
            foreach ($ingredients as $ingredient){
                 if($ingredient != $last_key) {
                 ?>
                 <?= $ingredient->getName() . "  - ";?>
                 <?php }
                 else{ ?>
                 <?= $ingredient->getName();?>
                 <?php
                 }
                 }?>
                 </td>
             </tr>
            <?php  }
            foreach ($ingredients as $ingredient){
            ?>
            <tr id="row<?= $ingredient->getId();?>">
            <?php if(isset($ingredients_inMenu) && in_array($ingredient->getId(), $ingredients_inMenu)) {  ?> <td><input type='checkbox' name='ingredients[]' value='<?=$ingredient->getId();?>' checked="true"></td><?php } 
             else{ ?>  <td><input type='checkbox' name='ingredients[]' value='<?=$ingredient->getId();?>'><?php } ?>
                <td> <?= $ingredient->getId(); ?></td>
                <td> <?= $ingredient->getName(); ?></td>
                <td> <?= $ingredient->getName(); ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="form-group">
            <label for="name"><?= translate("Plats maximum");?></label>
            <input type="number" step="1" min="1" max="5" class="form-control" value="1" name="recipes_num" id="recipes_num" required>
            <label for="name"><?= translate("Ingredients maximum");?></label>
            <input type="number" step="1"  min="1" max="5" value="1" class="form-control" name="ingredients_num" id="ingredients_num" required>
        </div>

        <div class="form-group">
            <label for="name"><?= translate("Prix du menu");?></label>
            <input type="number"  step="0.01" class="form-control" name="price" id="price" value="<?=$price;?>" required>
        </div>

        <?php if(isset($menu)) { ?> 
            <button type="button" class="btn btn-primary" onclick="updateForm()">
            <i class="fas fa-flask"></i> <?= translate('Modifier');?>
        </button>   <?php } else { ?>
            <button type="button" class="btn btn-primary" onclick="submitForm()">
            <i class="fas fa-flask"></i> <?= translate('Ajouter');?>
        </button>
        <?php } ?>
    </form>
</div>
<script type="text/javascript">
    function submitForm(){

     //   console.log($('#formAdd').serialize());

        var error = false;

        if (error === true){
            swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez remplir tous les champs'
            })
        }else{
            $.ajax({
                url : '/api/menu',
                type: 'POST',
                data: $('#formAdd').serialize()
            }).done(function(data){
                if (data.status !== "error"){
                    Swal.fire({
                        title: "<?= translate('Succès');?>",
                        text: "<?= translate("Le Menu a bien été ajouté");?>",
                        icon: 'success'
                    }).then((result) => {
                        document.location = "listofMenu"
                    })
                }else{
                    Swal.fire({
                        title: '<?= translate("Erreur");?>',
                        text: "<?= translate("Erreur lors de la création du Menu");?>",
                        icon: "error"
                    });
                }
            });
        }
    }

    function updateForm(){
        var error = false;

if (error === true){
    swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: 'Veuillez remplir tous les champs'
    })
}else{
    $.ajax({
        url : '/api/menu',
        type: 'POST',
        data: $('#formAdd').serialize()
    }).done(function(data){
        if (data.status !== "error"){
            Swal.fire({
                title: "<?= translate('Succès');?>",
                text: "<?= translate("Le Menu a bien été ajouté");?>",
                icon: 'success'
            }).then((result) => {
                document.location = "listofMenu"
            })
        }else{
            Swal.fire({
                title: '<?= translate("Erreur");?>',
                text: "<?= translate("Erreur lors de la création du Menu");?>",
                icon: "error"
            });
        }
    });
}


    }
</script>



