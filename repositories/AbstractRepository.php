<?php 
require_once __DIR__ . "/../utils/database/DatabaseManager.php";
class AbstractRepository{
	private $dbManager;

	public function __construct(){
		$this->dbManager = new DatabaseManager();
	}
	
	private function getDbTable(){
		$className = get_class($this);
		return strtolower(explode("Repository", $className)[0]);
	}

	public function getAll(): array{
		return $this->dbManager->getAll("SELECT * FROM " . $this->getDbTable());
	}

	public function getOneById(int $id){
		return $this->dbManager->find("SELECT * FROM " . $this->getDbTable() . " WHERE id = ?" ,[
			$id
		]);
	}

}