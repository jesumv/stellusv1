<?php
	global $remiact;
	global $c;
			
	
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
		
/***lee el numero de remision ***/
		$remiact= $funcbase->numremi($mysqli)+1;
/*** fija el numero de remision cancelada ***/		
		if(isset($_GET['r'])){
				$c = $_GET['r'];
//obtiene los datos de la remision cancelada
				$stinic = new otrasdbutils;
				$artrem = array();
				$artrem = $stinic->consultaremi($mysqli,$c,2,2);
				$art1 = $artrem[0][5];
			}
		
		function oprimio($mysqli,$remiact){
	
	//esta funcion hace las consultas de actualizacion
		$cance = $_POST ['remcanc'];
		$table = 'remisiones';
	    $idcliente =strtoupper($_POST ['idclientes']) ;
		$fecha =strtoupper($_POST ['fecha']) ;
	    $usu = $_SESSION['login_user'];
		$cliente = $_POST ['cliente'];
		$sucursal = $_POST ['sucursal'];
		$idsuccliente = $_POST ['idsuccliente'];
		$agente = $_POST ['idrepresentantes'];
		$doctor= $_POST ['doctor'];
		$procedimiento = $_POST ['procedimiento'];
		$paciente = $_POST ['paciente'];
		$registro = $_POST ['registro'];
		$subtotal =  $_POST ['insubtotal'];
		$iva =  $_POST ['iniva'];
		$total =  $_POST ['intotal'];
		$intotletra = $_POST ['intotletra'];
		$canc = "ESTA REMISION SUBSTITUYE A LA NO. ".$cance;

//cambio del status de la remision de vigente a cancelada
				$cancrem = new otrasdbutils;
				$resultrem= $cancrem->cancelarem($mysqli,$cance,$remiact);
		
//insercion de la nueva remision en la tabla de remisiones		
	    $sqlCommand= "INSERT INTO $table (idremisiones,fecha,idremitido,usu,status,cliente,sucursal,agente,doctor,procedimiento,
	    paciente,registro,subtotal,iva,total,con_letra,tiporem,obser)
	    VALUES ($remiact,'$fecha','$idcliente','$usu',0,'$cliente','$sucursal','$agente','$doctor','$procedimiento','$paciente','$registro',
	    $subtotal,$iva,$total,'$intotletra',0,'$canc')";
			
	    // Execute the query here now
	    $query=mysqli_query($mysqli, $sqlCommand) or die ("error en insercion remisiones: ".mysqli_error($mysqli)); 
//insercion de los articulos en la tabla de artremisiones--------------------------------------------------------

		//obtencion de valores del html 
			$table = 'artremision';
			$codigo=  $_POST ['cod0'];
			$precio = $_POST ['inprecio0'];
			$cantidad = $_POST ['cant0'];
			$importe= $_POST ['inimpor0'];
			$usu = $_SESSION['login_user'];
			$sqlCommand= "INSERT INTO $table (codigo,remision,precio_unitario,cantidad,importe)
	    	VALUES ('$codigo',$remiact,$precio,$cantidad,$importe)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("Error en insercion articulos: ".mysqli_error($mysqli)); 
//insercion en la tabla de inventarios-----------------------------------------------------------	    	
	   		//obtencion de valores
	   		$idproductos= $_POST ['inidprod0'];
			//obtencion del numero de almacen origen
			if($sucursal==""){
				$almacen = $idcliente.'0';	
			}else{
				$almacen = nalmac($idcliente,$idsuccliente);
			}
		
	   //disminucion de almacen de origen 		
	   		$table = 'inventarios';
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',9999,2,-$cantidad,$remiact,'$usu',1)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("Error en disminucion inventarios centrales: "
	    	.mysqli_error($mysqli)); 
	   //Incremento en almacen de destino
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',$almacen,1,$cantidad,$remiact,'$usu',1)";
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("Error en incremento almacen cliente: ".mysqli_error($mysqli));
			 
		}//fin de la funcion oprimio
	
	
		if(isset($_POST['enviorem'])){
			
			if(!isset($_GET['r'])){
				oprimio($mysqli,$remiact);
			/* cerrar la conexion */
	    		mysqli_close($mysqli); 
			// redirección a la hoja pdf mediante javascript 
				echo '<script type="text/javascript" language="Javascript">
		    				window.open("php/rremision.php?r='.$remiact.'");
                  	  </script>';
				header("location:constodas.php");

			}
				    
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
	$(document).ready(function(){
		
	/*variables globales en el script */
	var precio3;

		$('#cliente').focus(); 
	    $('#cliente').autocomplete({
			autoFocus: true,
			source: "get_client_list.php",
            minLength: 2,
            select: function( event, ui ) {
            		$("#cliente").val(ui.item.label);
					$( "#idclientes" ).val( ui.item.idclientes );
					$( "#razon" ).val( ui.item.razon );
					$("#rfc").val(ui.item.rfc);
					$("#domicilio").val(ui.item.domicilio);
					$("#nivel").val(ui.item.nivel);
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
            /*obtencion de valores de la pagina */
 							$("#idrepresentantes").val( ui.item.idrepresentantes);
 							$("#agente").val( ui.item.label);
 							var trans1 = $("#razon").val();
 							var trans2 = $("#sucursal").val();
 							var trans3 = $("#rfc").val();
 							var trans4 = $("#fecha").val();
 							var trans5 = $("#agente").val();
 							var trans6 = $("#domicilio").val();
 							var trans7 = $("#idclientes").val();
 							var trans8 = $("#nivel").val();
 							var trans9 = $("#remcanc").val();
 			/*consulta para obtener los articulos de la remision */				
 							$.getJSON("ajax/get_rem_data.php", {rem: trans9}, function(data){
			        		var cod0 = data[0].cod;
			        		var cant0 = data[0].cant;
			        		var cant = data[0].cant;
			        		var idprod = data[0].idprod;
			        			/* escritura de valores obtenidos en la pagina */
            				$("#clientprint").append(" "+trans1);
            				$("#sucurprint").append(" "+trans2);
            				$("#rfcprint").append(" "+trans3);
            				$("#fechaprint").append(" "+trans4);
            				$("#agentprint").append(" "+trans5);
            				$("#domiprint").append(" "+trans6);
            				$("#cod0").append(cod0);
            				$("#cant0").append(cant0);
		            /*datos para definir el precio */
			        		var nivel = $("#nivel").val();
            /*construcción y escritura de la descripción*/
				           	$.getJSON("ajax/get_prod_data.php", {idproductos: idprod , nivel: nivel}, function(data){
				           			var cliente = $("#idclientes").val();
				           			var desc = data[0].desc;
				           			var alg = data[0].alg
				        			precio3 = data[0].precio;
				        			var preciof = $.number(precio3,2);
				        			
				//si el cliente es grupo angeles, se le agrega el ALG
					        		if(cliente == 2){
						        		$("#des0").append(desc+" ALG-"+alg)	
						        		}else{ $("#des0").append(desc)
						        				$("#indes0").val(desc);
					        			}
					   				$("#precio0").append(preciof);
					        		$("#inprecio0").val(precio1);
					        		$("#inidprod0").val(idprod);
					        		var cantf = Number(cant0);
									var precio2 = Number(precio3);
									var importe = precio2 * cantf;
									var importef=$.number(importe,2);
									$("#impor0").append(importef);
									$("#inimpor0").val(importe);
									$("#subtotal").append(importef);
									$("#insubtotal").val(importe);
									var iva = calciva(importe);
									var ivaf=$.number(iva,2);
									$("#iva").append(ivaf);
									$("#iniva").val(iva);
									var total = (importe+iva).toString();
									var totalf = $.number(total,2);
									$("#total").append(totalf);
									$("#intotal").val(total);
									var totletra = covertirNumLetras(total);
									$("#intotletra").val(totletra);
									$("#totletra").append(totletra);
					        		
								});
        		
				

            	$("#doctor").focus();
			        			
							});
            						
            }  
        });
        
           	  
        validaforma();
	});
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "CAMBIO REMISION VENDEDOR A CLIENTE";
  include_once "include/barrasup.php";
  echo "<h3 class='substituye'>SUBSTITUYENDO A REMISION NO. ".$c."</h3>";
  ?> 
 

<br />
 <form id="remitir" action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
     
     <div class="error" style="display:none;">
            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
            <span ></span><br clear="all" />
     </div>
        
	 <div class = "ui-widget-header">
	    <table>
	        <legend>Datos de la Remisión:</legend>
	        <tr>
	            <td><label for="cliente">Cliente: </label></td>
	            <td class="field"><input type="text" id="cliente"  name="cliente" class="requer"/></td>
	            <input type="hidden" id="remcanc" name ="remcanc" value="<?php echo $c; ?>"/ >
                <input type="hidden" id="razon" class="ui-autocomplete-content"/>
                <input type="hidden" id="idclientes" name="idclientes"/>
                <input type="hidden" id="rfc" class="ui-autocomplete-content"/>
                <input type="hidden" id="domicilio" class="ui-autocomplete-content" length= "200"/>
                <input type="hidden" id="nivel" class="ui-autocomplete-content"/>
	            <td><label for="sucursal">Sucursal: </label></td>
                <td class="field"><td><input type="text" id="sucursal"  name="sucursal"/></td></td>
                <input type="hidden" id="idsuccliente" name="idsuccliente"/>
                <td><label for="fecha">Fecha: </label></td>
                <td class="field"><td><input type="text" id="fecha"  name="fecha" class="requer"/></td> </td>
                <td><label for="agente">Agente: </label></td>
                <td class="field"><td><input type="text" id="agente"  name="agente" class="requer"/></td></td>
	            <input type="hidden" id="idrepresentantes" name="idrepresentantes"/>  
	        </tr>   
        </table>
     </div>

	 	
<p></p>       

	<table id= "remision" class="tablap">
			<tr>
				<td id= "logo" rowspan="3" colspan = "5"><img src="img/logoremis.jpg" /></td>
				<td class="colorida" colspan = "2">REMISION</td>
			</tr>
			<tr>
				<td class="contenido" colspan = "2">N.<?php echo $remiact?></td>
			</tr>
			<tr>
				<td class="colorida" colspan = "2"></td>
			</tr>		
				

			<tr>
				<td colspan="3" id="clientprint"><b>CLIENTE:</b>:</td>
				<td colspan="3" id="sucurprint"><b>SUCURSAL:</b></td>              
				<td colspan="2" id="rfcprint"><b>RFC:</b></td>					
			</tr>
			<tr>	
				<td colspan="3" id="fechaprint"><b>FECHA:</b></td>	
				  <td colspan="4" id="agentprint"><b>AGENTE:</b></td>	
			</tr>
			<tr><td colspan="7" id="domiprint"><b>DOMICILIO:</b></td></tr>
				
	</table>
<p></p>
<div>
	<table class="tablap">
		<tr><td class="coloridachic">DOCTOR</td><td><input type = "text" id="doctor" name = "doctor" size = "60"/></td>
			<td class="coloridachic">PROCEDIMIENTO</td><td><input type = "text" id="procedimiento" name = "procedimiento" size = "60"/></td>
		</tr>
		<tr><td class="coloridachic">PACIENTE</td><td><input type = "text" id="paciente" name ="paciente" size = "60"/></td>
			<td class="coloridachic">N.REGISTRO</td><td><input type = "text" id="registro" name = "registro" size= "60"/></td>
		</tr>
	</table>
</div>
<p></p>
<div>
	<table class="tablap">
		<th>Código</th><th>Descripción</th><th>Precio Unitario</th><th>Cantidad</th><th>Importe</th>
		<?php
		for($i=0;$i<13;$i++){
			echo"<tr>
				<td class = 'celda' id='cod$i' name ='cod$i'></td>
				<td id='des$i'></td><input type='hidden' id='indes$i' name ='indes$i' /><input type='hidden' id='inidprod$i' name ='inidprod$i' />
				<td id= 'precio$i'></td><input type='hidden' id='inprecio$i' name ='inprecio$i' />
				<td id='cant$i' name='cant$i'></td>
				<td id = 'impor$i'></td><input type='hidden' id='inimpor$i' name ='inimpor$i' />
			</tr>";	
		}
		echo "<tr><td colspan='3'>OBSERVACIONES: ESTA REMISION SUBSTITUYE A LA NO.".$c."</td>
		<td class='coloridachic'>SUBTOTAL</td><td id='subtotal'></td><input type='hidden' name = 'insubtotal' id='insubtotal'/></tr>"
		
		?>
		
		<tr><td colspan="3"></td><td class="coloridachic">I.V.A.</td><td id="iva"></td><input type="hidden" name = "iniva" id="iniva"/></tr>
		<tr><td class="coloridachic">TOTAL CON LETRA</td><td colspan="2" id="totletra"></td><input type="hidden" name = "intotletra" id="intotletra"/>
		<td class="coloridachic">TOTAL</td><td id="total"></td><input type="hidden" name = "intotal" id="intotal"/></tr>
		
	</table>
	
</div>
<p></p>
<div class="centraelem"><input type="submit" name ="enviorem" value="IMPRIMIR REMISION"/></div>
</form>
<div id="footer"></div>


</body>
