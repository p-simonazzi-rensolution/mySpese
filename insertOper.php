<?php

require_once 'class/database.php';
require_once 'class/operazione.php';
require_once 'class/categorie.php';



$objCategorie = new Categoria();
$objCategorie->__constructor();
$arrCategorie = $objCategorie->getList();



?>

<html>
	<head>
<script src="js/jquery-1.10.2.js" type="text/javascript" ></script>
<script type="text/javascript">
	function setCurrentDate() {
		var now = new Date();
		strYear = now.getFullYear();
		strMonth = (now.getMonth()<9) ? "0"+(now.getMonth()+1) : now.getMonth()+1;
		strDay = (now.getDay()<9) ? "0"+(now.getDay()+1) : now.getDay()+1; 
		str = strYear+"/"+strMonth+"/"+strDay;
		
		$("#data").val(str);
	}
	
</script>

</head>
<body>		

<form action="server/serverEventHandler.php" method="POST">
	<div><label for="date">Data:</label><input type="date" name="date" id="data" placeholder="yyyy-mm-dd" required="required" />
		<input type="button" onclick="setCurrentDate()" value="Oggi">
	</div>
	<div><label for="importo">Importo:</label><input type="number" step="0.01" name="importo" placeholder="&euro; 13,23"/>
		</div>
	<div><label for="categoria">Categoria:</label><select name="categoria">
		<option></option>
		<?php 
			foreach ($arrCategorie as $categoria) {
				$id = $categoria['idCategoria'];
				$value = $categoria['descrizione'];
				
				echo "<option value='$id' > $value</option>\n";
			}
		?>
	</select></div>
	<div><textarea name="note"></textarea></div>
	<input type="hidden" name="action" value="saveNewOperation" />
	<input type="button" value="Salva" onclick="submit()">
	<!--div><input type="submit" value="Salva" /></div-->
</form>


