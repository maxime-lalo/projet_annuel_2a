<div class="container-fluid txt-container">
<?php

require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';

if (isset($_POST['firstname']) && isset($_POST['lastname']) &&
    isset($_POST['password']) && isset($_POST['mail']) && isset($_POST['phone'])
    && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['number'])) {

    $manager = new DatabaseManager();
    $authService = new AuthService($manager);
    $user = $authService->subscribeClient($_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['mail'], $_POST['phone']
        , $_POST['address'], $_POST['number'], $_POST['city']);
    if ($user === null) {
        echo ('Ce Mail est déja utilisé');
        die();
    }
    http_response_code(201);
} else {
    http_response_code(400);
}

