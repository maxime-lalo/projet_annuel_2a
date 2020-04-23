<?php 
require_once __DIR__ . "/../utils/database/DatabaseManager.php";
class AbstractRepository{
	protected $dbManager;

	public function __construct(){
		$this->dbManager = new DatabaseManager();
	}
	
	protected function getDbTable():string{
		$className = get_class($this);
		$explode = explode("Repository", $className);
        $pieces = preg_split('/(?=[A-Z])/',$explode[0]);
        unset($pieces[0]);
        foreach ($pieces as $key => $piece) {
            $pieces[$key] = strtolower($pieces[$key]);
        }
		return implode('_',$pieces);
	}

	protected function getClassName():string{
	    $className = get_class($this);
        return explode("Repository", $className)[0];
    }

	public function getAll(){
		$strClass = $this->getClassName();
		$all = $this->dbManager->getAll("SELECT * FROM " . $this->getDbTable());
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
		$strClass = $this->getClassName();
		return new $strClass($item);
	}

}