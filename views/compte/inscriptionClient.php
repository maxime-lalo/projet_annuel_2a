<form method="post" action="insertClient">
    <br>
    <div class="container-fluid txt-container">

        <h3 align="center"><?= translate("Inscription Client"); ?></h3>
        <br>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstname">Prénom</label>
                <input required type="text" class="form-control" name="firstname">
            </div>
            <div class="form-group col-md-6">
                <label for="lastname">Nom</label>
                <input required type="text" class="form-control" name="lastname">
            </div>
            <div class="form-group col-md-6">
                <label for="mail">Email</label>
                <input required type="email" class="form-control" name="mail">
            </div>
            <div class="form-group col-md-6">
                <label for="password">Mot de passe</label>
                <input required type="password" class="form-control" name="password">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Rue</label>
                <input required type="text" class="form-control" name="address" placeholder="1234 Main St">
            </div>
            <div class="form-group col-md-4">
                <label for="number">Numéro</label>
                <input required type="number" class="form-control" name="number">
            </div>


            <div class="form-group col-md-6">
                <label for="city">Ville</label>
                <input required type="text" class="form-control" name="city">
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Telephone</label>
                <input required type="tel" class="form-control" name="phone">
            </div>

        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </div>
</form>