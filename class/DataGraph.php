<?php
/**
 * Created by PhpStorm.
 * User: Vladeck
 * Date: 15/09/2015
 * Time: 00:09
 */

require_once "database.php";

class DataGraph {

    /**
     * Data in formato: yyyy-mm-dd
     * @var string
     */
    protected $dtIntervalStart;

    /**
     * Data in formato: yyyy-mm-dd
     * @var string
     */
    protected $dtIntervalEnd;


    protected $graphType;


    /**
     * Imposta i limiti temporali della query:
     * - la data iniziale e' obbligatoria
     * - la data finale è quella attuale se non specificato diversamente
     *
     * @param string $start_data
     * @param string $end_data
     * @return void
     */
    public function setDataInterval($start_data, $end_data = "now") {
        try {
            $DTstart = new DateTime($start_data);
            $this->dtIntervalStart = $DTstart->format("Y-m-d");
        }
        catch (Exception $e) {die("DataGraph::setDataInterval() - errore nella data di inizio");};

        try {
            $DTend = new DateTime($end_data);
            $this->dtIntervalEnd = $DTend->format("Y-m-d");
        }
        catch (Exception $e) {die("DataGraph::setDataInterval() - errore nella data di fine");};
    }










    public function getMonthGroupByType($date_start = "now") {
        $objDatabase = new Database();
        $mysqli = $objDatabase->getConnection();

        $diMonth = new DateInterval("P2M");
        $dtStart = new DateTime($date_start);
        $dtStart->sub($diMonth);
        $dtEnd = new DateTime("now");

        $this->dtIntervalStart = $dtStart->format("Y-m-d");
        $this->dtIntervalEnd = $dtEnd->format("Y-m-d");


        $query = "SELECT sp_categorie.descrizione, SUM(importo*ent_usc) AS totale
			FROM sp_operazione
			LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
			WHERE data >= '$this->dtIntervalStart'
			AND data <= '$this->dtIntervalEnd'
			GROUP BY sp_categorie.idCategoria
			ORDER BY totale DESC
			LIMIT 0,30";
        if (! $result = $mysqli->query($query) ) die("Errpre query : $query");

        $arrResult = array();

        while ($row = $result->fetch_assoc()) {
            $row['totale'] = number_format($row['totale'],2,'.','');
            $arrResult[] = $row;
        }

        return $arrResult;
    }


    public function getLastMonth($date_start = "now") {
        $objDatabase = new Database();
        $mysqli = $objDatabase->getConnection();

        $diMonth = new DateInterval("P1M");
        $dtStart = new DateTime($date_start);
        $dtStart->sub($diMonth);
        $dtEnd = new DateTime("now");

        $this->dtIntervalStart = $dtStart->format("Y-m-d");
        $this->dtIntervalEnd = $dtEnd->format("Y-m-d");


        //spese degli ultimi 30 gg
        $query = "SELECT data, SUM(importo*ent_usc) AS totale
			FROM sp_operazione
			LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
			WHERE data>= '$this->dtIntervalStart'
			  AND data<= '$this->dtIntervalEnd'
			GROUP BY data
			LIMIT 0,30";

        if (! $result = $mysqli->query($query) ) die("Errpre query : $query");

        $arrResult = array();

        $andamento = 0;
        while ($row = $result->fetch_assoc()) {
            $row['totale'] = number_format($row['totale'],2,'.','');
//            $DTdata = new DateTime($row['data']);
//            $row['data'] = $DTdata->format("d");
//            unset($row['ent_usc']);
            $andamento += $row['totale'];
            $row['andamento'] = $andamento;
            $arrResult[] = $row;
        }

        return $arrResult;
    }

    public function getMeanValue($idCat = null) {
        $objDatabase = new Database();
        $mysqli = $objDatabase->getConnection();

        $query = "SELECT AVG(importo*ent_usc)
                  FROM sp_operazione
			      LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
			      WHERE data>= '{$this->dtIntervalStart}'
			        AND data<= '{$this->dtIntervalEnd}' ";
        if ($idCat!=null) $query .= " AND sp_operazione.idCategoria = $idCat";

        if (! $result = $mysqli->query($query) ) die("Errpre query : $query");
        $arrResult = array();
        while ($row = $result->fetch_assoc()) {
            $arrResult[] = $row;
        }

        return $arrResult;
    }

    public function getMeanValueMonthly($idCat = null) {
        $objDatabase = new Database();
        $mysqli = $objDatabase->getConnection();

        $query = "SELECT COUNT(*), SUM(importo*ent_usc), AVG(importo*ent_usc)
                  FROM sp_operazione
			      LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
			      WHERE data>= '{$this->dtIntervalStart}'
			        AND data<= '{$this->dtIntervalEnd}' ";
        if ($idCat!=null) $query .= " AND sp_operazione.idCategoria = $idCat ";


        if (! $result = $mysqli->query($query) ) die("Errpre query : $query");
        $arrResult = array();
        while ($row = $result->fetch_assoc()) {
            $arrResult[] = $row;
        }

        return $arrResult;
    }


    public function getYearGroupByMonth($date_start = "now") {
        $objDatabase = new Database();
        $mysqli = $objDatabase->getConnection();

        $diMonth = new DateInterval("P1Y");
        $dtStart = new DateTime($date_start);
        $dtStart->sub($diMonth);
        $dtEnd = new DateTime("now");

        $this->dtIntervalStart = $dtStart->format("Y-m-d");
        $this->dtIntervalEnd = $dtEnd->format("Y-m-d");

        //spese totali suddivise per mesi
        $query = "
			SELECT MONTH(data) AS mese, SUM(importo*ent_usc) AS totale
			FROM sp_operazione
			LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
			WHERE data>= '{$this->dtIntervalStart}'
			  AND data<= '{$this->dtIntervalEnd}'
			GROUP BY MONTH(data)
			ORDER BY data ASC
			LIMIT 0,30";

        if (! $result = $mysqli->query($query) ) die("Errpre query : $query");

        $arrResult = array();

        while ($row = $result->fetch_assoc()) {
            $arrResult[] = $row;
        }

        return $arrResult;
    }

} 