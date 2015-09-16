<?php
/* 
 * © Paolo Simonazzi 2014
 * DbManager - class Database
 */

class Database {
  public $name = "my_paolosimonazzi";
  private $host = "localhost";
  private $password = "lordvladeck89";
  private $user = "paolosimonazzi";
  
  public $debug = false;
  
  private $mysqli;
  
  public function __constructor() {
      //creo la connessione col DB e restitisco l'oggetto mysqli
      //la variabile debug mi distingue i casi online o offline
      
      //return $this->getConnection();
  }
  
  public function debugConnection() {
        $this->host = "localhost";
        $this->name = "spese";
        $this->password = "gattosilvestro";
        $this->user = "root";
  }
  
  public function toArray() {
    $array = array();
    $array['name'] = $this->name;
    
    return $array;
  }
  
  public function getConnection() {
    
	if ($this->debug==true) {
		$this->debugConnection();
	}
	
      $mysqli = new mysqli($this->host,$this->user, $this->password);
      $mysqli->select_db($this->name);
      
      $this->mysqli = $mysqli;
      
      return $this->mysqli;
  }
  
  public function changeDatabase($database) {
    //andrebbe gestito l'output...
    $this->name = $database;
    
  }
  
  
}



/*
$my = new Database();
$mysqli = $my->getConnection();
print_r($mysqli);*/

?>