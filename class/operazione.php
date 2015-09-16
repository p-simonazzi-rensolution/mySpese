<?php

require_once "database.php";

  class Operazione {
  	/** @var */
    private $data;
    private $importo;
    private $idCategoria;
	
	private $table_name;
	
	public function __constructor() {
		
		$this->table_name = "sp_operazione";
		
	}
    
   
	public function save($data, $importo, $idCategoria, $note) {
		$db = new Database();
		$this->mysqli = $db->getConnection();
		$this->table_name = "sp_operazione";
		
		echo $query = "INSERT INTO ".$this->table_name." (data, importo, idCategoria, note) VALUES ('$data', $importo, $idCategoria, '$note');";
		if (! $result = $this->mysqli->query($query) ) die("Errroe durante la query $query");
		
	}
	
	
	
    
    
  }


	class Categoria {		
		private $id;
		private $descrizione;
		private $idCategoriaPadre;
		
		/** mysqli */
		public $mysqli;
		
		public function __constructor() {
			$db = new Database();
			$this->mysqli= $db->getConnection();
			$this->table_name = "sp_categorie";
			
		}
		
		
		public function getList() {
			$db = new Database();
			$this->mysqli = $db->getConnection();
			
			$query = "SELECT idCategoria, descrizione FROM ".$this->table_name." ORDER BY descrizione";
			if (! $result = $this->mysqli->query($query)) die("Errroe durante la query $query");
			
			$arrout = array();
			
			while ($row = $result->fetch_assoc()) {
				array_push($arrout, $row);
			}
			
			return $arrout;
		}
		
		
		
		public function save($descrizione) {
			$db = new Database();
			$this->mysqli = $db->getConnection();
			$this->table_name = "sp_categorie";
			
			$query = "INSERT INTO ".$this->table_name." (descrizione, idCategoriaPrincipale) VALUES ('$descrizione', NULL);";
			if (! $result = $this->mysqli->query($query)) die("Errroe durante la query $query". $this->mysqli->error);
		}			
		
	}
	
	




	







?>







