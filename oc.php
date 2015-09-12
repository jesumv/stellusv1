<?php
//funciones auxiliares
require '/include/funciones.php';
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
	/** se oprimio el boton **/
		function oprimio($mysqli){
		//insercion en la tabla de inventarios	    	
	   		//obtencion de valores
	   		$idproductos= $_POST ['idprod'];
			$fecha =strtoupper($_POST ['fecha']) ;
			$sucursal =$_POST ['sucursal'];
			$cantidad = $_POST ['cant'];
			$ref= $_POST ['fact'];
			$usu = $_SESSION['login_user'];
			$idclientes = $_POST ['idclientes'];
			$nosuc = $_POST ['idsuccliente'];
			$idsuccliente = decidesuc($idclientes, $nosuc);
			$oc= $_POST ['oc'];
			$subtotal= $_POST ['subtot'];
			$iva= $_POST ['iva'];
			$total= $_POST ['total'];
			$rem = $_POST ['rem'];
			$observaciones = $_POST ['obser'];
			
		//disminución en almacen de destino
			$table="inventarios";
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha','$idsuccliente',2,-$cantidad,$ref,'$usu',4)";
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("Error en cambio inventario: ".mysqli_error($mysqli)); 
			
		//insercion en la tabla de facturas
			//obtencion del numero de movimiento del inventario
			$sql= "SELECT MAX(idinventarios) FROM inventarios";
            $result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
			$invact= $result2[0];
			$table = 'facturas';
	   		$sqlCommand= "INSERT INTO $table (no_factura,fecha,oc,idproductos,cant,subtotal,iva,total,idsuccliente,
	   		idclientes,remision,observaciones,usu,idinventarios)
	    	VALUES ($ref,'$fecha',$oc,$idproductos,$cantidad,$subtotal,$iva,$total,'$idsuccliente',$idclientes,'$rem','$observaciones',
	    	'$usu',$invact)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("Error en insercion factura: ".mysqli_error($mysqli)); 

		}
		
		if(isset($_POST['enviodato'])){
			
		    oprimio($mysqli);
			
			/* cerrar la conexion */
	    	mysqli_close($mysqli);  
			header('Location: consfactura.php');
		}
		
	} else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
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
<link rel="stylesheet" type="text/CSS" href="css/remisiones.css" />
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="js/funaux.js">	</script>
<script type="text/javascript" src="js/jquery.number.js">	</script>
<script src="js/jquery.validate.js"></script>
<script src="js/validaciones.js"></script>
<script src="js/additional-methods.js"></script>

<script>
	$(document).ready (function(){
		
		$('#cliente').focus(); 
				
	    $('#cliente').autocomplete({
			autoFocus: true,
            source: "get_client_list.php",
            minLength: 2,
            select: function( event, ui ) {
					$( "#idclientes" ).val( ui.item.idclientes );
					$("#nivel").val(ui.item.nivel);
					$('#sucursal').focus(); 
				}
				                
        });
        $('#sucursal').autocomplete({
			autoFocus: true,
            source: function(request, response) {
    		$.getJSON("get_sucur_list2p.php", { term: $('#sucursal').val(), cte: $('#idclientes').val() }, 
              response);
  				},
            minLength: 2,
            select:function(event, ui){
            	$("#idsuccliente").val(ui.item.noalmacen);
            	$('#fecha').focus();
            }			                
        }); 
        
		    $('#fecha').datepicker({
		dateFormat: "yy-mm-dd",
		onClose: function(dateText, inst) {
		      $('#cod').focus();
		   }	  
	   });
	          
        $('#cod').autocomplete({
        	autoFocus: true,
        	source: "get_prod_list.php",
        	minLength: 2,
        	select:function(event,ui){
        		event.preventDefault();
        		var codigo = ui.item.codigo
        		this.value = codigo;
        		var des0 = ui.item.desc;
        		var alg = ui.item.alg;
        		var cliente = $("#idclientes").val();
        		var idproducto =  ui.item.idproductos;
        		$("#idprod").val(idproducto);
        		var nivel = $("#nivel").val();
        		if(cliente == 21){
        		$("#des").val(des0+" ALG-"+alg)	
        		}else{ $("#des").val(des0)}
        		$.getJSON("php/get_precio.php", {idproductos: idproducto , nivel: nivel}, function(data){
        			var precio1 = data[0].precio;
        			var preciof = $.number(precio1,2);
	   				$("#precio").val(preciof);
	   				$("#inprecio").val(precio1);
	   				
				});
        		$("#cant").focus();
        	}

        })
        
        $( "#cant" ).change(function() {
        	var precio = $("#inprecio").val();
        	var cant = parseInt(this.value);
			var importe = precio* cant;
			var importef=$.number(importe,2);
			$("#impor").val(importef);
			$("#subtot").val(importe);
			var iva = calciva(importe);
			$("#iva").val(iva);
			var total = (importe+iva).toString();
			$("#total").val(total);
			$("#fact").focus();
		});
		
		$( "#fact" ).change(function() {
			$("oc").focus();
		});
		$( "#oc" ).change(function() {
			$("rem").focus();
		});
		
		$( "#rem" ).change(function() {
			$("obser").focus();
		});
		
    validaforma(); 	  
        
	});
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "ORDENES DE COMPRA";
  include_once "include/barrasup.php";
  ?> 
 <div class = "centraelem">
    <h4>Los campos marcados con <span class="req">*</span>  son requeridos</h4>
  </div>

<br />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
 	
 	 <div class="error" style="display:none;">
            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
            <span ></span><br clear="all" />
      </div>
      
      
	 <div class = "ui-widget-header" id="contenido">
	 	<table>
	 		<tr><th><legend>Datos de la Orden de Compra:</legend></th></tr>		
			 	<tr>
				 	<td><label for="cliente">Cliente: </label></td>
			 		<td class="field"><input type="text" id="cliente"  name="cliente" class="ui-autocomplete-content" class = "requer"/><span class='req'>*</span></td>
				 	<td><label for="sucursal">Sucursal: </label></td>
				 	<td class="field"><input type="text" id="sucursal"  name="sucursal" class="ui-autocomplete-content"/></td>
				 	<input type="hidden" id="idsuccliente" name="idsuccliente"/> 
				 	<td><label for="fecha">Fecha: </label></td>
				 	<td class="field"><input type="text" id="fecha"  name="fecha" class = "requer"/><span class='req'>*</span></td>
				 </tr>
				 <tr></tr>
				 <tr>
				 	<td><label for="cod">Codigo: </label></td>
				 	<td colspan="3" class="field"><input type='text' id='cod' name ='cod' class = "requer"/><span class='req'>*</span></td>
				 	<td><label for="cant">Cantidad: </label></td>
					<td class="field"><input type='number' id='cant' name='cant'class = "requer"/><span class='req'>*</span></td>
			 	</tr>
			 		<input type="hidden" id="razon"/>
				 	<input type="hidden" id="idclientes" name="idclientes"/>
				 	<input type="hidden" id="nivel"/>
			 	<tr>
			 	<td><label for="des">Descripción: </label></td>
			 	<td class="field"><input type='text' id='des' name ='des' disabled /></td>
			 	<input type='hidden' id='idprod' name ='idprod' />
			 	<td><label for="precio">Precio Unitario: </label></td>
			 	<td class="field"><input type='text' id='precio' name ='precio' disabled/></td>
			 	<input type='hidden' id='inprecio' name ='inprecio' />
			 	<td><label for="impor">Importe: </label></td>
				<td class="field"><input type='text' id='impor' name ='impor' disabled/></td>
			 	</tr>
			 	<tr>
				<input type='hidden' id='subtot' name ='subtot' />
				<input type='hidden' id='iva' name ='iva' />
				<input type='hidden' id='total' name ='total' />
				<td ><label for="fact">Factura: </label></td>
				<td class="field"><input type='text' id='fact' name ='fact' class = "reqer"/><span class='req'>*</span></td>
				<td><label for="oc">Orden de compra: </label></td>
				<td class="field"><input type='text' id='oc' name ='oc' /></td>
				<td><label for="rem">Remisión: </label></td>
				<td class="field"><input type='text' id='rem' name ='rem' /></td>
				</tr>
				<tr>
				<td><label for="obser">Observaciones: </label></td>
				<td  class="field" colspan="5"><input type='text' id='obser' name ='obser' size='100'/></td>
				</tr>	
		</table>	
					  
	 </div>
<div class="centraelem"><input type="submit" name ="enviodato" value="ENVIAR DATOS"/></div>
</form>
<div id="footer"></div>


</body>
