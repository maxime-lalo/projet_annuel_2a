<?php 
require_once __DIR__ . "/../utils/database/DatabaseManager.php";
class AbstractRepository{
	private $dbManager;

	public function __construct(){
		$this->dbManager = new DatabaseManager();
	}
	
	private function getDbTable():string{
		$className = get_class($this);
		return explode("Repository", $className)[0];
	}

	public function getAll(){
		$strClass = $this->getDbTable();
		$all = $this->dbManager->getAll("SELECT * FROM " . $strClass);
		$items = array();
		foreach ($all as $key => $item) {
			$items[] = new $strClass($item);
		}
		return $items;
	}

	public function getOneById(int $id){
		$item = $this->dbManager->find("SELECT * FROM " . $this->getDbTable() . " WHERE id = ?" ,[
			$id
		]);
		$strClass = $this->getDbTable();
		return new $strClass($item);
	}

}