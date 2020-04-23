<div class="container-fluid txt-container">
<?php
require_once __DIR__ . '/../../services/auth/AuthService.php';
require_once __DIR__ . '/../../utils/database/DatabaseManager.php';

if (isset($_POST['firstname']) && isset($_POST['lastname']) &&
    isset($_POST['password']) && isset($_POST['mail']) && isset($_POST['phone'])
    && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['number']) && isset($_FILES['CvToUpload'])) {

    $target_dir = "uploads/";
    $cvFileType = strtolower(pathinfo( $_FILES["CvToUpload"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . 'CV_' . $_POST['firstname'] . '_' . $_POST['lastname'] . '.' . $cvFileType;
    $uploadOk = 1;


// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["CvToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($cvFileType != "docx" && $cvFileType != "pdf" && $cvFileType != "odt") {
        echo "Sorry, only PDF & DOCX & ODT files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["CvToUpload"]["tmp_name"], $target_file)) {
            echo "The CV has been uploaded.";
            $manager = new DatabaseManager();
            $authService = new AuthService($manager);
            $user = $authService->subscribeWorker($_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['mail'], $_POST['phone']
                , $_POST['address'], $_POST['number'], $_POST['city']);
            if ($user === null) {
                echo ('Ce mail est déjà utilisé');
                die();
            }
            http_response_code(201);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    http_response_code(400);
}
?><?php
