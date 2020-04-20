<?php

require_once __DIR__ . '/../../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../../models/user.php';


class AuthService
{

    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    // Todo functions subscribe, connexion , etc ...


    public function subscribeClient(string $firstname, string $lasname, string $password, string $email, string $phone, string $street_name,
                                    int $street_number,
                                    string $city): ?User
    {

        if ($this->exists($email))
            return null;

        $hashed = hash('sha256', $password);
        $affectedRows = $this->db->exec('INSERT INTO user (firstname,lastname, password,email, phone,street_name, street_number, city, is_client , is_employe, is_worker, date_register ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0 , 0 , NOW())', [
            $firstname,
            $lasname,
            $hashed,
            $email,
            $phone,
            $street_name,
            $street_number,
            $city
        ]);
        if ($affectedRows === 0) {
            return null;
        }
        return new User(
            $this->db->getLastInsertId(),
            $firstname,
            $lasname,
            $hashed,
            $email,
            $phone,
            $street_name,
            $street_number,
            $city);
    }

    public function subscribeWorker(string $firstname, string $lasname, string $password, string $email, string $phone, string $street_name,
                                    int $street_number,
                                    string $city): ?User
    {

        if ($this->exists($email))
            return null;

        $hashed = hash('sha256', $password);
        $affectedRows = $this->db->exec('INSERT INTO user (firstname,lastname, password,email, phone,street_name, street_number, city, is_client , is_employe, is_worker, date_register, activated ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0 , 1 , NOW(), 0)', [
            $firstname,
            $lasname,
            $hashed,
            $email,
            $phone,
            $street_name,
            $street_number,
            $city
        ]);
        if ($affectedRows === 0) {
            return null;
        }
        return new User(
            $this->db->getLastInsertId(),
            $firstname,
            $lasname,
            $hashed,
            $email,
            $phone,
            $street_name,
            $street_number,
            $city);
    }

    public function log(string $mail, string $password): ?int
    {
        $hashed = hash('sha256', $password);
        $userData = $this->db->find('SELECT id FROM user WHERE email = ? AND password = ?', [
            $mail,
            $hashed
        ]);
        if ($userData === null) {
            return null;
        }
        return $userData['id'];
    }

    public function getUserFromId(int $id): ?User
    {

        $userData = $this->db->find('SELECT firstname, lastname ,password , email , phone , street_name , street_number , city FROM user WHERE id = ?', [
            $id
        ]);
        if ($userData === null) {
            return null;
        }
        return new User(
            $id,
            $userData['firstname'],
            $userData['lastname'],
            $userData['password'],
            $userData['email'],
            $userData['phone'],
            $userData['street_name'],
            $userData['street_number'],
            $userData['city']);

    }

    public function exists(string $email): bool
    {
        $affectedRows = $this->db->exec('SELECT id FROM User WHERE email = ?', [$email]);
        return $affectedRows !== 0;
    }

    public function updateUser(string $firstname, string $lastname, string $email,
                               string $phone, string $street_name, string $street_number, string $city, int $id): ?User
    {
        $userData = $this->db->exec('Update user SET firstname = ?, lastname = ? , email =? , phone = ?, street_name = ?,street_number = ? , city = ? WHERE id = ?', [
            $firstname,
            $lastname,
            $email,
            $phone,
            $street_name,
            $street_number,
            $city,
            $id
        ]);
        if ($userData === null) {
            return null;
        }
        return $this->getUserFromId($id);
    }

}