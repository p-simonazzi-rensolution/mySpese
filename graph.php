




<html>
  <head>
  	<?php
  	
	require_once "class/database.php";
	require_once "class/operazione.php";
    require_once "class/DataGraph.php";


    $dataGraph = new DataGraph();
    $dataGraph->setDataInterval("2015-05-06");

    $arrData = $dataGraph->getLastMonth();


	$strOUT = "[";
  	foreach ($arrData as $riga) {
        if (strlen($strOUT)==1) {
            $strOUT .= "[ ";
            foreach ($riga as $key => $val) {
                $strOUT .= " '$key', ";
            }
            $strOUT = substr($strOUT, 0,strlen($strOUT)-2);
            $strOUT .= " ],";
        }

  		$strOUT .= " [";
        foreach ($riga as $key => $val) {
            if (is_numeric($val)) {
                $strOUT .= " $val, ";
            }
            else {
                $strOUT .= " '$val', ";
            }
        }
        $strOUT = substr($strOUT, 0,strlen($strOUT)-2);
		$strOUT .= "], ";
  	}
	$strOUT = substr($strOUT, 0,strlen($strOUT)-2);
	$strOUT .= " ]";
  	
  	?>
  	
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

	var strArray = <?php printf($strOUT);?>

      // Create the data table.
      var data = new google.visualization.arrayToDataTable(strArray);

      // Set chart options
      var options = {'title':'Spese degli ultimi 10 giorni',
                     'width':800,
                     'height':300};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>

  </head>

  <body>
<!--Div that will hold the pie chart-->
    <div id="chart_div" style="width:400; height:300"></div>

  </body>
</html>