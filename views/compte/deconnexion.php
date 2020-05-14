<?php
session_start();
setcookie('user_id');
unset($_COOKIE['user_id']);
session_destroy();

header('Location: connexion');

