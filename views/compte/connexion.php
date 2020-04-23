<form method="post" action="logging">
    <br>
    <div class="container-fluid txt-container">
        <h3 align="center"><?= translate("Connexion");?></h3>
        <br>
    <div class="form-group">
        <label for="mail">Email address</label>
        <input required type="email" class="form-control" name="mail" aria-describedby="emailHelp">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" name="password">
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="check">
        <label class="form-check-label" for="check">Check me out</label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
