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
	
	$estador = new otrasdbutils;
    
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
		
		$( document ).ready(function() {
            $( ".ed" ).click(function(eventObject ) {
                eventObject.preventDefault();
                var currentId = $(this).attr('id');
                window.open('cambremision.php?r='+currentId,'_self')
            });
            
        });
	</script>
	
</head>
 <body>
 	<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
	  $titulo = "REMISIONES EMITIDAS";
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
	 
	 <!--TABLA DE RESULTADOS----------------------------------------------------------------->
		<div class="centraelem"> 
		<?php
		//seleccion de remisiones 
			if(isset($_POST['enviocons'])){
			$fechainic= $_POST['fechai'];
			$fechafin= $_POST['fechaf'];
		//se eligio fecha	
			 $sqlCommand = "SELECT idremisiones, fecha,cliente, sucursal, obser,status,tiporem 
		  FROM remisiones WHERE fecha >='$fechainic' AND fecha <='$fechafin' ORDER BY idremisiones";
		}else{
				//SI NO SE ELIGIO
		  $sqlCommand = "SELECT idremisiones, fecha,cliente, sucursal, obser,status,tiporem 
		  FROM remisiones  WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= fecha ORDER BY idremisiones";
			
		}
		
		  // Execute the query here now
		 $query1=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN CONSULTA DE INVENTARIOS CLIENTES. ".mysqli_error($mysqli));
		 
		 echo "	<table class= 'db-table'>";
		 echo "<tr><th>CANCELAR</th><th>CAMBIAR</th><th>REMISION</th><th>FECHA</th><th>REMITIDO</th><th>OBSERVACIONES</th><th>STATUS</th></tr>";
		 
				 while($row2=mysqli_fetch_row($query1)){
				 	$remitido = $row2[2]." ".$row2[3];
				 /*obtener el nombre del status */
				 	$stat = $estador->estadorem($row2[6],$row2[5]);
					$id = $row2[0];
				 	echo "<tr><td class= can id='c$id'><a href='cambremision.php'><img src='img/cancel.jpg' ALT='cancelar'></a></td>
				 	<td class= ed id='$id'><a href='cambremision.php' ><img src='img/inout.jpg' ALT='cambiar'></a></td>
				 	<td><a href='php/rremision.php?r=$row2[0]'>$row2[0]</a></td><td>$row2[1]</td>
				 	<td>$remitido</td><td>$row2[4]</td><td>$stat</td></tr>";
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