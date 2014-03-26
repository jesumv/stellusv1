<?php
    /*** Autoload class files ***/ 
    function __autoload($class){
      require('include/' . strtolower($class) . '.class.php');
    }
    
    $funcbase = new dbutils;
/*** conexion a bd ***/
    $mysqli = $funcbase->conecta();
    if (is_object($mysqli)) {
/*** checa login***/
        $funcbase->checalogin($mysqli);
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
	
	<link rel="stylesheet" href="css/cupertino/jquery-ui-1.10.4.custom.css">
	<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
	<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
	<link rel="stylesheet" type="text/CSS" href="css/remisiones.css" />
	<link rel="shortcut icon" href="img/logomin.gif" />
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	
	<title>STELLUS MEDEVICES</title>
	
	
</head>
 <body>
 	<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
	  $titulo = "CONSULTA DE REMISIONES EN BLANCO";
	  include_once "include/barrasup.php";
	 //------consultas a la base de datos------
	  
 	?>
	 
		<div class="centraelem"> 
		<?php
			//seleccion de remisiones marcadas en blanco
		 $sqlCommand = "SELECT idremisiones, custodio
		  FROM remisiones  WHERE tiporem = 2";
		  // Execute the query here now
		 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE INVENTARIOS CLIENTES. ".mysqli_error($mysqli));
		 
		 echo "	<table class= 'db-table'>";
		 echo "<tr><th>REMISION</th><th>REPRESENTANTE</th></tr>";
		 
				 while($row2=mysqli_fetch_row($query1)){
				 	
				 	echo "<tr><td><a href='consremision.php?r=$row2[0]'>$row2[0]</a></td><td>$row2[1]</td></tr>";
				 } 
		 
		 echo "	</table>";
		 
		/* liberar la serie de resultados */
		  mysqli_free_result($query1);
		  ?>
	  
		</div> 
		
		
		 
<?php
/* cerrar la conexión */
  mysqli_close($mysqli);
?>
		 
	<div id="footer"></div>
 
 </body>