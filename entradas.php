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
	
function oprimio($mysqli){
//esta funcion hace las consultas de actualizacion
	$table = 'inventarios';
    $idproductos = $_POST ['idproductos'] ;
	$fecha = $_POST ['fecha'] ;
	$referencia = $_POST ['referencia'] ;
	$obser = $_POST ['obser'] ;
	$cantidad = $_POST ['cantidad'] ;
	$usu = $_SESSION['username'];
//1 es almacen central. 1 es tupo movimiento entrada, status 1 es en transito
	$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad, referencia,
	observaciones,usu,status)
    VALUES ('$idproductos','$fecha',1,1,$cantidad,'$referencia','$obser','$usu',0)"
        or die('insercion cancelada '.$table);	
    // Execute the query here now
    $query = mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
	
	if($query){
	    /* cerrar la conexion */
	    mysqli_close($mysqli); 
		return 0;
	}
	else{return 1;}
}
	
/***si se oprimio el boton de accion***/

if(isset($_POST['enviaentra'])){
    $resp1 = oprimio($mysqli);
	if($resp1 == 0)	{
		echo '<script type="text/javascript">
             window.alert("Inventario Añadido correctamente!");
    	</script>';
	}
	else{
		echo '<script type="text/javascript">
             window.alert("Error en alta de inventario.");
    	</script>';
	}
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="sp">
<head>
<meta charset="ISO-8859-1">

<title>STELLUS MEDEVICES</title>

<link rel="stylesheet" href="css/cupertino/jquery-ui-1.10.4.custom.css">
<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>

<script>
   $(function() {
   		$('#corto').focus();              
	    $('#corto').autocomplete({
					autoFocus: true,
	                source: "get_prod_list.php",
	                minLength: 2,
	                select: function( event, ui ) {
							$( "#idproductos" ).val( ui.item.idproductos );
							$( "#codigo" ).val( ui.item.codigo );
						}
						                
	            });
	    
	    $('#fecha').datepicker({
	    	dateFormat: "yy-mm-dd",
	    	onClose: function(dateText, inst) {
		      $('#referencia').focus();
		   }	  
	    	  
	});
		$( "#dialog" ).dialog({ autoOpen: false });
	
	});
	
	$('#corto').change(function() {
  		$('#fecha').focus;	
});

	

  </script>
</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "ALMACEN";
  include_once "include/barrasup.php";
  ?> 

 
<div>


	<legend>Entrada de artículos a almacén</legend>
	             
	            <p>Introduzca el nombre del producto</p>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">	            
	<div class = "ui-widget">
			<label for="corto">Producto: </label> 
			<input type="text" id="corto"  name="corto" class="ui-autocomplete-content"/> 
			<input type="hidden" id="idproductos"  name="idproductos" />                
			<label for="corto">Codigo: </label> 
			<input type="text" id="codigo"  name="codigo" disabled/>
			<label for="fecha">Fecha: </label> 
			<input type="text" id="fecha" name="fecha" />
			<label for="referencia">Referencia: </label> 
			<input type="text" id="referencia"  name="referencia"/>
	</div>
	<p>
		<label for="cantidad">Cantidad: </label> 
		<input type="number" id="cantidad"  name="cantidad"/>			
		<label for="obser">Observaciones: </label> 	
		<textarea id ="obser" name="obser" rows="3" cols="20"></textarea>
	</p>
	<p><input type='submit' name='enviaentra' value = 'Enviar'/></p>
	
</form>
<br/>
<br />
<div id="footer">
</div>


</body>
