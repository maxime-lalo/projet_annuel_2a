<?php
require_once __DIR__ . "/RecipeRepository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/FoodRepository.php";
require_once __DIR__ . "/FoodTruckRepository.php";
require_once __DIR__ . "/../models/Ca.php";

class CaRepository extends AbstractRepository
{

    public function getByDate(string $date_begin , string $date_end): ?array
    {


        $ca_lines = array();
        $all_ca = $this->dbManager->getAll("SELECT * FROM ca WHERE date between ? and ?",[
            $date_begin,
            $date_end
        ]);
        if($all_ca){
                foreach($all_ca as $ca) {
                    try {$ca_lines[] = new Ca($ca);
                    } catch (Exception $e) {
                    }
                }

            return $ca_lines;

        }else{
            return NULL;
        }
    }
}