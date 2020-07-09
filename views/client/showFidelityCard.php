<?php
require_once __DIR__ . "/../../utils/qrcode/qrlib.php";
require_once __DIR__ . "/../../repositories/UserRepository.php";

QrCode::png("|id:".$user->getId()."-fst:".$user->getFirstName()."-lst:".$user->getLastName()."|");
