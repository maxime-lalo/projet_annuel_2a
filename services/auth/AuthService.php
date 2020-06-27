<?php

require_once __DIR__ . '/../../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../../models/User.php';


class AuthService
{

    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }


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
        return new User([
            "id" => $this->db->getLastInsertId(),
            "firstname" => $firstname,
            "lastname" => $lasname,
            "password" => $hashed,
            "email" => $email,
            "phone" => $phone,
            "street_name" => $street_name,
            "street_number" => $street_number,
            "city" => $city
        ]);
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
        return new User([
            "id" => $this->db->getLastInsertId(),
            "firstname" => $firstname,
            "lastname" => $lasname,
            "password" => $hashed,
            "email" => $email,
            "phone" => $phone,
            "street_name" => $street_name,
            "street_number" => $street_number,
            "city" => $city
        ]);
    }

    public function log(string $mail, string $password): ?int
    {
        $hashed = hash('sha256', $password);
        $userData = $this->db->find('SELECT id, activated FROM user WHERE email = ? AND password = ?', [
            $mail,
            $hashed
        ]);
        if ($userData === null) {
            return -1;
        }elseif($userData['activated'] == 0){
            return -2;
        }
        return $userData['id'];
    }

    public function getUserFromId(int $id): ?User
    {
        $userData = $this->db->find('SELECT * FROM user WHERE id = ?', [
            $id
        ]);
        if ($userData === null) {
            return null;
        }
        return new User([
            "id" => $id,
            "firstname" => $userData['firstname'],
            "lastname" => $userData['lastname'],
            "password" => $userData['password'],
            "email" => $userData['email'],
            "phone" => $userData['phone'],
            "points" => $userData['points'],
            "street_name" => $userData['street_name'],
            "street_number" => $userData['street_number'],
            "city" => $userData['city'],
            "is_client" => $userData['is_client'],
            "is_worker" => $userData['is_worker'],
            "food_truck_id" => $userData['food_truck_id'],
            "is_admin" => $userData['is_admin'],
        ]);

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

    public function updateMdpUser(int $id, string $password): ?User
    {
        $hashed = hash('sha256', $password);
        $userData = $this->db->exec('Update user SET password = ? WHERE id = ?', [
            $hashed,
            $id
        ]);
        if ($userData === null) {
            return null;
        }
        return $this->getUserFromId($id);
    }

}