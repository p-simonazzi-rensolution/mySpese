<?php

require_once 'class/database.php';
require_once 'class/operazione.php';
require_once 'class/categorie.php';





?>

<html>
	<head>
<script src="js/jquery-1.10.2.js" type="text/javascript" ></script>


</head>
<body>		

<form action="server/serverEventHandler.php" method="POST">
	<div><label for="descrizione">Categodia:</label><input type="text" name="descrizione"/></div>
	
	<input type="hidden" name="action" value="saveNewCateg" />
	<input type="button" value="Salva" onclick="submit()">
	<!--div><input type="submit" value="Salva" /></div-->
</form>


