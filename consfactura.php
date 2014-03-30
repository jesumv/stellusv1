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
	
	<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.css">
	<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
	<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
	<link rel="shortcut icon" href="img/logomin.gif" />
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	
	<title>STELLUS MEDEVICES</title>
	
	<script>
	$(function(){
		$('#fechai').datepicker({
		dateFormat: "yy-mm-dd",
		onClose: function(dateText, inst) {
		      $('#fechaf').focus();
		   }	  
	   });
	   	$('#fechaf').datepicker({
		dateFormat: "yy-mm-dd",
		onClose: function(dateText, inst) {

		   }	  
	   });
	});
	
	$(document).ready(function() {
     $(".botonExcel").click(function(event) {
     $("#datos_a_enviar").val( $("<div>").append( $("#tablaaxl").eq(0).clone()).html());
     $("#FormularioExportacion").submit();
		});
	});
	</script>
</head>
 <body>
 	<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
	  $titulo = "CONSULTA DE FACTURACION";
	  include_once "include/barrasup.php";
	 //------consultas a la base de datos------
	  
 	?>
 	
 <!--FORMA PARA LA SELECCION DE FECHAS DE LA CONSULTA----------------------------------------------------------------->  	
<div class="centraelem">
	<div class = "ui-widget-header" >
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
			<legend>Fechas de Consulta:</legend>
		 	<label for="fechai">Fecha Inicial: </label>
			<input type="text" id="fechai"  name="fechai"/>
			<label for="fechaf">Fecha Final: </label>
			<input type="text" id="fechaf"  name="fechaf"/>
			<input type="submit" name ="enviocons" value="CONSULTAR"  />
	 	</form>
	</div>
</div> 
<p></p>
	 
		<div class="centraelem"> 
		<?php
			//CONSULTA DE FACTURAS
				//SI SE ELIGIERON FECHAS
		if(isset($_POST['enviocons'])){
			$fechainic= $_POST['fechai'];
			$fechafin= $_POST['fechaf'];
			
			 $sqlCommand = "SELECT t1.no_factura,t1.oc,t1.remision,t1.fecha,t2.razon_social,t3.nom_sucursal,t1.subtotal,t1.iva,t1.total, 
		 t4.nom_corto, t5.nom_corto,t1.cant, t1.observaciones FROM facturas AS t1 INNER JOIN clientes AS t2 ON t1.idclientes=t2.idclientes 
		 LEFT JOIN succliente as t3 on t1.idsuccliente=t3.no_almacen LEFT JOIN representantes as t4 ON t1.agente = t4.idrepresentantes
		 LEFT JOIN productos as t5 ON t1.idproductos = t5.idproductos
		 WHERE fecha >='$fechainic' AND fecha <='$fechafin' ORDER BY t1.no_factura";
		}else{
				//SI NO SE ELIGIERON
			$sqlCommand = "SELECT t1.no_factura,t1.oc,t1.remision,t1.fecha,t2.razon_social,t3.nom_sucursal,t1.subtotal,t1.iva,t1.total,
		t4.nom_corto, t5.nom_corto,t1.cant,t1.observaciones FROM facturas AS t1 INNER JOIN clientes AS t2 ON t1.idclientes=t2.idclientes LEFT JOIN succliente as t3 on 
		t1.idsuccliente=t3.no_almacen LEFT JOIN representantes as t4 ON t1.agente = t4.idrepresentantes
		LEFT JOIN productos as t5 ON t1.idproductos = t5.idproductos WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= t1.fecha  ORDER BY t1.no_factura";
			
		}
		
		  // Execute the query here now
		 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE INVENTARIOS CLIENTES. ".mysqli_error($mysqli));
		 //construccion de la casilla remision/oc
		 
		 echo "	<table border=1 id='tablaaxl' >";
		 echo "<tr><th>FACTURA</th><th>REM/OC</th><th>FECHA</th><th>CLIENTE</th><th>SUCURSAL</th></th><th>AGENTE</th><th>SUBTOTAL</th>
		 <th>IVA</th><th>TOTAL</th><th>PRODUCTO</th><th>PIEZAS</th><th>OBS.</th></tr>";
		 
				 while($row2=mysqli_fetch_row($query1)){
				 	$comb = $row2[2]."/".$row2[1];
					echo "<tr>";
					 	echo "<td>$row2[0]</td>";
						echo "<td>$comb</td>";
						echo "<td>$row2[3]</td>";
						echo "<td>$row2[4]</td>";
						echo "<td>$row2[5]</td>";
						echo "<td>$row2[9]</td>";
						echo "<td>$row2[6]</td>";
						echo "<td>$row2[7]</td>";
						echo "<td>$row2[8]</td>";
						echo "<td>$row2[10]</td>";
						echo "<td>$row2[11]</td>";
						echo "<td>$row2[12]</td>";
						
					 echo "</tr>";
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

<div class= "centraelem">
	<form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
	<p>Exportar a Excel  <img src="img/export_to_excel.gif" class="botonExcel" /></p>
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>
	
</div>

		 
	<div id="footer"></div>
 
 </body>