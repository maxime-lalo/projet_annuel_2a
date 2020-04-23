<?php

require_once __DIR__ . '/../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../models/truck.php';

class TruckService
{
    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }


    public function getTruckFromUserId(int $id): ?Truck
    {
        $truck = $this->db->find('SELECT * FROM FOOD_TRUCK WHERE id = (select FOOD_TRUCK_id FROM user where id = ?)', [
            $id
        ]);
        if ($truck === null) {
            return null;
        }
        return new Truck(
            $truck['id'],
            $truck['date_register'],
            $truck['date_last_check'],
            $truck['mileage'],
        );

    }

}