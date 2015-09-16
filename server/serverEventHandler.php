<?

$action = $_POST['action'];
$data = $_POST['data'];


switch ($action) {
	case "saveNewOperation" : {
		
		require_once "../class/operazione.php";
		
		$objOperation = new Operazione();
		$objOperation->save($_POST['date'], $_POST['importo'], $_POST['categoria'], $_POST['note']);	
		
		header('Location: ../list.php');
		
	}
	break;
	
	case "saveNewCateg" : {
		
		require_once "../class/operazione.php";
		
		$objCategoria = new Categoria();
		$objCategoria->save($_POST['descrizione']);	
		
		header('Location: ../list.php');
		
	}
	break;
	default : {
		echo "Errore: evento non gestito";
	}
} 





?>