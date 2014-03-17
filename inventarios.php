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
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	<title>STELLUS MEDEVICES</title>
	
	
	<script>
		$(function () { $("#secciones").tabs(); });
	</script>
</head>
 <body>
 	<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
	  $titulo = "CONSULTA DE INVENTARIOS";
	  include_once "include/barrasup.php";
	 //------consultas a la base de datos------
	  
 	?>

		
	
	<div id="secciones" class="style1">
		 <ul> 
		 	<li><a href="#tab-1">Stellus</a>
		 	</li> <li><a href="#tab-2">Hospitales</a></li>
		 	</li> <li><a href="#tab-3">Representantes</a></li>
		 </ul>
		<div id="tab-1" class="centraelem"> 
			<h2>INVENTARIOS EN OFICINA CENTRAL</h2>
<?php
			//inventarios en stellus
	 $sqlCommand = "SELECT t1.idproductos, t2.nom_corto, SUM(t1.cantidad) AS total FROM inventarios AS t1 INNER JOIN productos AS t2 
	 ON t1.idproductos = t2.idproductos WHERE t1.almacen = 2000 GROUP BY t2.idproductos";
	  // Execute the query here now
	 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE INVENTARIOS CLIENTES. ".mysqli_error($mysqli));
	 
	 echo "	<table class= 'db-table'>";
	 echo "<tr><th>PRODUCTO</th><th>total</th></tr>";
	 
			 while($row2=mysqli_fetch_row($query1)){
			 	echo "<tr><td>$row2[1]</td><td>$row2[2]</td></tr>";
			 } 
	 
	 echo "	</table>";
	 
/* liberar la serie de resultados */
  mysqli_free_result($query1);
  ?>
  
		</div>
		<div id="tab-2" class="centraelem"> <h2>INVENTARIOS EN HOSPITALES</h2>
<?php
			//inventarios en hospitales
	 $sqlCommand = "SELECT t1.idproductos, t1.almacen,t2.nom_corto, SUM(t1.cantidad) AS total FROM inventarios AS t1 INNER JOIN productos AS t2 
	 ON t1.idproductos = t2.idproductos WHERE t1.almacen <1000 GROUP BY t1.almacen,t2.idproductos";
	 
	  // Execute the query here now
	 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE INVENTARIOS CLIENTES. ".mysqli_error($mysqli));
	 
	 echo "	<table class= 'db-table'>";
	 echo "<tr><th>ALMACEN</th><th>PRODUCTO</th><th>total</th></tr>";
	 
			 while($row2=mysqli_fetch_row($query1)){
			 	echo "<tr><td>$row2[1]</td><td>$row2[2]</td><td>$row2[3]</td></tr>";
			 } 
	 
	 echo "	</table>";
?>
		</div>
		
		<div id="tab-3" class="centraelem"> <h2>INVENTARIOS EN CUSTODIA DE REPRESENTANTES</h2></div>  
		 </div>
		 
<?php
/* cerrar la conexión */
  mysqli_close($mysqli);
?>
		 
	<div id="footer"></div>
 
 </body>