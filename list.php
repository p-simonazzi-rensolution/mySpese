<?php

require_once "class/database.php";
require_once "class/operazione.php";



$db = new Database();
$mysqli = $db->getConnection();

$query = "SELECT data, importo, descrizione, note
		FROM sp_operazione
		LEFT OUTER JOIN sp_categorie ON sp_categorie.idCategoria = sp_operazione.idCategoria
		WHERE data>= (NOW()- INTERVAL 10 DAY)
		ORDER BY data ASC
		LIMIT 0,30";
if (! $result = $mysqli->query($query) ) die("Errpre query : $query");

?>

<table border="1">
	<thead>
		<tr><th>Data</th><th>Importo &euro;</th><th>Tipologia</th><th>Note</th></tr>
	</thead>
	<tbody>
		<?php
		while ($row = $result->fetch_assoc()) {
			$data = $row["data"];
			$importo = $row["importo"];
			$categoria = $row["descrizione"];
			$note = $row["note"];
			
			$importo = round($importo,2);
			$importo = number_format($importo, 2);
			
			echo "<tr><td>$data</td><td style='text-align: right'>$importo</td><td>$categoria</td><td>$note</td></tr>";
			
		}
		
		?>
	</tbody>	
</table>


<button value="Aggiungi nuova operazione" onclick="location.href='insertOper.php'" >Aggiungi nuova operazione</button>
<button value="Aggiungi nuova categoria" onclick="location.href='insertCat.php'" >Aggiungi nuova categoria</button>

qui ho fatto una modifica

e poi ancora un'altra<br><br>
ed ora un'altra ancora

qui dove sono nel branch o nel master?
