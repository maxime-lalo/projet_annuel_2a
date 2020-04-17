<?php

require_once __DIR__ . '/../../utils/databases/DatabaseManager.php';


class AuthService {

    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    // Todo functions subscribe, connexion , etc ...

}