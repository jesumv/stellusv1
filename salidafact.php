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
	/** se oprimio el boton **/
		function oprimio($mysqli){
		//insercion en la tabla de inventarios	    	
	   		//obtencion de valores
	   		$idproductos= $_POST ['idprod'];
			$fecha =strtoupper($_POST ['fecha']) ;
			$agente =$_POST ['idrepresentantes'];
			$sucursal =$_POST ['sucursal'];
			$cantidad = $_POST ['cant'];
			$ref= $_POST ['fact'];
			$usu = $_SESSION['login_user'];
			$idclientes = $_POST ['idclientes'];
			$idsuccliente = $_POST ['idsuccliente'];
			$oc= $_POST ['oc'];
			$subtotal= $_POST ['subtot'];
			$iva= $_POST ['iva'];
			$total= $_POST ['total'];
			$observaciones = $_POST ['obser'];
			$otros = $_POST ['razon'];
			
		//construccion del numero de almacen
			$almacen = $idclientes.$idsuccliente;
	   //disminucion de almacen central   		
	   		$table = 'inventarios';
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',2000,2,-$cantidad,$ref,'$usu',5)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("error en alta de inventarios".mysqli_error($mysqli)); 
		//Incremento en almacen de destino
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',$almacen,1,$cantidad,$ref,'$usu',5)";

	    	$query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
		//insercion en la tabla de facturas
			//obtencion del numero de movimiento del inventario
			$sql= "SELECT MAX(idinventarios) FROM inventarios";
            $result = mysqli_query($mysqli,$sql);
            $result2 = mysqli_fetch_row($result);
			$invact= $result2[0];
			
			$table = 'facturas';
	   		$sqlCommand= "INSERT INTO $table (no_factura,fecha,oc,idproductos,cant,subtotal,iva,total,agente,idsuccliente,
	   		idclientes,observaciones,usu,idinventarios,otros_clientes)
	    	VALUES ($ref,'$fecha',$oc,$idproductos,$cantidad,$subtotal,$iva,$total,$agente,$almacen,$idclientes,'$observaciones',
	    	'$usu',$invact,'$otros')";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("error en tabla facturas ".mysqli_error($mysqli)); 

		}
		
		if(isset($_POST['enviodato'])){
			
		    oprimio($mysqli);
			
			/* cerrar la conexion */
	    	mysqli_close($mysqli);  
			
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

<script>
	$(function(){
		
		var razonot = $( "#razonot" ),
		allFields = $( [] ).add(razonot);
		
		$('#cliente').focus();
		
		$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 250,
		width: 250,
		modal: true,
		zIndex:10000,
		position: { my: "center", at: "center", of: "#contenido" },
		buttons: {
			"ok": function() {
			allFields.removeClass( "ui-state-error" );
			$( "#cliente" ).val(razonot.val());
			$("#razon").val(razonot.val());
			$( this ).dialog( "close" );
			$('#fecha').focus();
			},
			Cancelar: function() {
			$( this ).dialog( "close" );
			}
		},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
			});
			
		 
	    $('#cliente').autocomplete({
			autoFocus: true,
            source: "get_client_list.php",
            minLength: 2,
            select: function( event, ui ) {
					$( "#idclientes" ).val( ui.item.idclientes );
					$("#nivel").val(ui.item.nivel);
					if($("#idclientes").val() == 1){
						$( "#dialog-form" ).dialog( "open" );
					}
					$('#sucursal').focus();
				}
				
			
				                
        });
        $('#sucursal').autocomplete({
			autoFocus: true,
            source: "get_sucur_list.php",
            minLength: 2,
            select:function(event, ui){
            	$("#idsuccliente").val(ui.item.idsuccliente);
            	$('#fecha').focus();
            }			                
        }); 
        
		    $('#fecha').datepicker({
		dateFormat: "yy-mm-dd",
		onClose: function(dateText, inst) {
		      $('#agente').focus();
		   }	  
	   });
	   
	   $('#agente').autocomplete({
			autoFocus: true,
            source: "get_agent_list.php",
            minLength: 2,
            select: function( event, ui ) {
            	var idrepresentantes =  ui.item.idrepresentantes;	
            	$("#idrepresentantes").val(idrepresentantes);								
            	$("#cod").focus();      						
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
        		if(cliente == 2){
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
    	  
        
	});
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "SALIDA POR FACTURACION";
  include_once "include/barrasup.php";
  ?> 
 
<!-- forma para captura de datos ------------------------------>
<div id="dialog-form" title="Otros Clientes" class="dialogo">
	<form action="#" method="post" name = "datos" id="datos">
		<fieldset class="cajita">
		<label for="razonot" class="cajita">Nombre o Razón Social</label>
		<input type="text" name="razonot" id="razonot" value="" class="text ui-widget-content ui-corner-all" class="cajita">
		</fieldset>
	</form> 
</div>

<br />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	 <div class = "ui-widget-header" id="contenido">
	 	<legend>Datos de la Factura:</legend>
			 	<label for="cliente">Cliente: </label>
			 	<input type="text" id="cliente"  name="cliente" class="ui-autocomplete-content"/>
			 	<input type="hidden" id="razon" name= "razon" class="ui-autocomplete-content"/>
			 	<input type="hidden" id="idclientes" name="idclientes"/>
			 	<input type="hidden" id="nivel" class="ui-autocomplete-content"/>
			 	<label for="sucursal">Sucursal: </label>
			 	<input type="text" id="sucursal"  name="sucursal" class="ui-autocomplete-content"/> 
			 	<input type="hidden" id="idsuccliente" name="idsuccliente"/>
			 	<label for="fecha">Fecha: </label>
			 	<input type="text" id="fecha"  name="fecha"/>   
			 	<label for="agente">Agente: </label>
			 	<input type="text" id="agente"  name="agente" class="ui-autocomplete-content"/>
			 	<input type='hidden' id='idrepresentantes' name ='idrepresentantes' />
			 	<label for="cod">Codigo: </label>
			 	<input type='text' id='cod' name ='cod' />
			 	<br />

			 	<label for="des">Descripción: </label>
			 	<input type='text' id='des' name ='des' size= "75" disabled /><input type='hidden' id='idprod' name ='idprod' />
			 	<label for="precio">Precio Unitario: </label>
			 	<input type='text' id='precio' name ='precio' disabled/>
			 	<input type='hidden' id='inprecio' name ='inprecio' />
			 	<label for="cant">Cantidad: </label>
				<input type='number' id='cant' name='cant'/>
				<br />
				<label for="impor">Importe: </label>
				<input type='text' id='impor' name ='impor' disabled/>
				<input type='hidden' id='subtot' name ='subtot' />
				<input type='hidden' id='iva' name ='iva' />
				<input type='hidden' id='total' name ='total' />
				<label for="fact">Factura: </label>
				<input type='text' id='fact' name ='fact' />
				<label for="oc">Orden de compra: </label>
				<input type='text' id='oc' name ='oc' />
				<label for="obser">Observaciones: </label>
				<input type='text' id='obser' name ='obser' size='100'/>
				
					  
	 </div>

<p></p>
<div class="centraelem"><input type="submit" name ="enviodato" value="ENVIAR DATOS"/></div>
</form>
<div id="footer"></div>


</body>
