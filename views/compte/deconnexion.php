<?php

setcookie('user_id', "", time() - 3600, '/');
unset($_COOKIE['user_id']);
session_destroy();

header('Location: connexion');