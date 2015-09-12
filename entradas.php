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
	$usu = $_SESSION['login_user'];
	$lote = $_POST ['lote'] ;
	$fechacad= $_POST ['fechacad'] ;
//9999 es almacen central. 1 es tipo movimiento entrada, status 1 es en transito
	$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad, referencia,
	observaciones,usu,status,lote,fecha_cad)
    VALUES ('$idproductos','$fecha',99999,1,$cantidad,'$referencia','$obser','$usu',0,'$lote','$fechacad')"
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
			 window.open("inventarios.php","_self");
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
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/validaciones.js"></script>
<script src="js/additional-methods.js"></script>


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
							$('#fecha').focus(); 
						}	                
	            });
	    
	    $('#fecha').datepicker({
	    	dateFormat: "yy-mm-dd",
	    	onClose: function(dateText, inst) {
		      $('#referencia').focus();
		   }	  
	    	  
	});
		$('#fechacad').datepicker({
	    	dateFormat: "yy-mm-dd",
	    	defaultDate: +365,
	    	onClose: function(dateText, inst) {
		      $('#cantidad').focus();
		   }	  
	    	  
	});
	
	$('#corto').change(function() {
  		$('#fecha').focus;	
	});
	
	       validaforma(); 
	
	});
		

  </script>
</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "ENTRADAS A ALMACEN";
  include_once "include/barrasup.php";
  ?> 
<div class = "centraelem">
    <h4>Los campos marcados con <span class="req">*</span>  son requeridos</h4>
</div>
 
<div class="centraelem">

	<form id="entra" action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	    
	    <div class="error" style="display:none;">
            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
            <span ></span><br clear="all" />
        </div>
        <b></b>
       <table class="db-table">
           <div class = "ui-widget-header">
               <tr>
                   <td><label for="corto">Producto: </label> </td>
                   <td class="field">
                    <input type="text" id="corto"  name="corto" class = 'requer'/><span class='req'>*</span>
                   </td>
                   <input type="hidden" id="idproductos"  name="idproductos" /> 
                   <td><label for="codigo">Codigo: </label> </td>
                   <td class="field"> <input type="text" id="codigo"  name="codigo" disabled/></td>
                   <td><label for="fecha">Fecha: </label> </td>
                   <td class="field"><input type="text" id="fecha" name="fecha"  class = 'requer'/>
                    <span class='req'>*</span></td>
                   <td><label for="referencia">Referencia: </label> </td>
                   <td class="field"><input type="text" id="referencia"  name="referencia"/></td>
               </tr>
                <tr>
                    <td><label for="lote">Lote: </label> </td>
                    <td class="field" colspan="2"><input type="text" id="lote"  name="lote" class = 'requer'/>
                    <span class='req'>*</span></td>
                    <td><label for="fechacad">Fecha de Caducidad: </label> </td>
                    <td class="field"><input type="text" id="fechacad"  name="fechacad" class = 'requer'/>
                    <span class='req'>*</span></td>
                    <td><label for="cantidad">Cantidad: </label></td>
                    <td class="field" colspan="2"><input type="number" id="cantidad"  name="cantidad" class = 'reqnum'/>
                    <span class='req'>*</span></td>                            
                </tr>
            
                <tr>
                    <td colspan="4"><label for="obser">Observaciones: </label></td>
                    <td class="field" colspan="2"><textarea id ="obser" name="obser" rows="3" cols="20"></textarea> </td>    
                </tr>
        
       </table>             
		
		<p><input type='submit' name='enviaentra' value = 'Dar Entrada'/></p>
		</div>
		
	</form>
</div>
<br/>
<br />
<div id="footer">
</div>


</body>
