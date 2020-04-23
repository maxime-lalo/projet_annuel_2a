<?php

require_once __DIR__ . '/../utils/database/DatabaseManager.php';
require_once __DIR__ . '/../models/warehouse.php';

class WarehouseService
{
    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function getWarehouseFromUserId(int $id): ?Warehouse
    {
        $warehouse = $this->db->find('SELECT * FROM WAREHOUSE WHERE id = (select WAREHOUSE_id FROM user where id = ?)', [
            $id
        ]);
        if ($warehouse === null) {
            return null;
        }
        return new Warehouse(
            $warehouse['id'],
            $warehouse['name'],
            $warehouse['street_name'],
            $warehouse['street_number'],
            $warehouse['city'],
        );

    }


}