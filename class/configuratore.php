<?php
/* 
 * © Paolo Simonazzi 2014
 * DbManager - class Configuratore
 */




include "database.php";

        
       
      
/*
 * tinyint_    1
boolean_    1
smallint_    2
int_        3
float_        4
double_        5
real_        5
timestamp_    7
bigint_        8
serial        8
mediumint_    9
date_        10
time_        11
datetime_    12
year_        13
bit_        16
decimal_    246
text_        252
tinytext_    252
mediumtext_    252
longtext_    252
tinyblob_    252
mediumblob_    252
blob_        252
longblob_    252
varchar_    253
varbinary_    253
char_        254
binary_        254
 * 
 * 
 * */



class Configuratore {
  /*
   * DEFINISCO LE COSTANTI DI ACCESSO AI DATI VIA FETCH_FIELD
   *  
   */
    const FLAG_NOT_NULL = 1;
    const FLAG_PRI_KEY = 2;                                                                              
    const FLAG_UNIQUE_KEY = 4;
/*  const FLAG_BLOB=16;                                                                                
    const FLAG_UNSIGNED=32;                                                                            
    const FLAG_ZEROFILL=64;                                                                            
    const FLAG_BINARY=128;                                                                             
    const FLAG_ENUM=256;*/                                                                               
    const FLAG_AUTO_INCREMENT =512;                                                                     
/*  const FLAG_TIMESTAMP=1024;                                                                         
    const FLAG_SET=2048;                                                                               
    const FLAG_NUM=32768;                                                                              
    const FLAG_PART_KEY=16384;                                                                         
    const FLAG_GROUP=32768;*/                                                                            
    const FLAG_UNIQUE = 65536;
  
    //costanti utilizzate per il comportamento da tenere all'esecuzione di una query
    const FETCH_ASSOC = 0;
    const FETCH_ROW = 1;
    const FETCH_FIELDS = 2;
  
  
    //questa classe si collega al database e effettua una mappatura di tutte le tabelle e dei relativi campi presenti
    private $arrTables = null;
    public $arrMatrix = null;
    /*  ogni singolo elemento dell'arrMatrix è formato nel seguente modo ed è associato ad una sola tabella del DB
     * MATRIX
     * -------------------------------------------------------------------------------------------------------------------
     * |         | type | lenght | alias | help | rif_table | rif_column | unique | required |
     * -------------------------------------------------------------------------------------------------------------------
     * | campo_1 |
     */
    
    
    public function __constructor() {
      
    }
    
    private function executeQuery($query, $type = self::FETCH_ASSOC, $database = null) {
      $conn = new Database();
      if ($database!=null) {
        $conn->changeDatabase($database);
      }
      $mysqli = $conn->getConnection();
      
      //array che conterrà il risultato da restituire
      $arrOutput = array();
      
      //echo "Query: $query <br>";
      //echo "Type: $type <br>";
      if (! $result = $mysqli->query($query) ) {
        $err = $query." errore: ".$mysqli->error;
        $mysqli->close();
        return $err;        
      }
      
      if ($result->num_rows>0) {
        
        if ($type==self::FETCH_ROW) {
          while ($row = $result->fetch_row() ) {
            array_push($arrOutput,$row);
          }
        }
        
        if ($type==self::FETCH_ASSOC) {
          while ($row = $result->fetch_assoc() ) {
            array_push($arrOutput,$row);
          }
        }
        
        
        
      }
      else {
        
        if ($type==self::FETCH_FIELDS) {
          $row = $result->fetch_fields();
          
          $arrOutput = $row;
          
        }
        else {
        $result->close();
        $mysqli->close();
        return 0;
        }
      }
      
      $result->close();
      $mysqli->close();
      unset($conn);
      return $arrOutput;
    }
    
    
    
    public function retriveTables() {      
      $query = "SHOW TABLES";
      $result = $this->executeQuery($query);

      foreach ($result as $table) {
        $this->arrTables[] =$table['Tables_in_spese'];
      }
      
    }
    
    public function retriveFeatures() {
      //nel caso non l'avessi fatto, recupero le tabelle appartenenti al DB
      if ($this->arrTables==null) {
        $this->retriveTables();
      }
      
      $array = array();
      
      foreach($this->arrTables as $currentTable) {
        //per ogni tabella recupero i campi e le relative proprietà
        
        $query = "SELECT * FROM $currentTable";
        $result = $this->executeQuery($query, Configuratore::FETCH_FIELDS);
        
        
        foreach ($result as $object) {
          //print_r($object);
          $arrOBJ = array();
          $arrOBJ['type'] = $object->type;
          $arrOBJ['length'] = $object->length;
          $arrOBJ['decimals'] = $object->decimals;
          
          
          //gestione del campo flag
          $arrOBJ['unique'] = ($object->flags & self::FLAG_UNIQUE) ? 1 : 0;
          $arrOBJ['primary'] = ($object->flags & self::FLAG_PRI_KEY) ? 1 : 0;
          
          
          //recupero delle informazioni dal database information_schema          
          $query2 = sprintf("SELECT REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='spese' AND TABLE_NAME='%s' AND COLUMN_NAME='%s';",$object->table, $object->name);
          $result2 = $this->executeQuery($query2, "ASSOC", "information_schema");
          
          $arrOBJ['rif_table'] = $result2[0]['REFERENCED_TABLE_NAME'];
          $arrOBJ['rif_column'] = $result2[0]['REFERENCED_COLUMN_NAME'];
          
          
          
          
          $arrColumn = array();
          $arrColumn[$object->name] = $arrOBJ;
          //print_r($arrColumn);
          
          $array[$currentTable][$object->name] = $arrOBJ;  
          
          
          
          //echo "<hr>";
        }
        
        
        
        
      
      $this->arrMatrix = $array;  
        
        
      }
    }
}


$tab = new Configuratore();
$tab->retriveFeatures();





foreach ($tab->arrMatrix as $tabella => $array2) {
        
        echo "<table border=1>";
        $firstLine = TRUE;
        foreach ($array2 as $key=> $array3) {
            
          if ($firstLine==TRUE) {
            echo "<tr><th>Tabella $tabella </th>";
              foreach ($array3 as $chiavi=>$campi) {
                echo "<th>$chiavi</th>";
              }
            echo "</tr>";
            $firstLine = FALSE; 
          }
          
          echo "<tr><td>$key</td>";
          
          foreach ($array3 as $campi) {
            
            echo "<td>$campi</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
      }
    

?>