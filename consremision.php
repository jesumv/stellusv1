<?php
	global $remiact;
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
		
/***lee el numero de remision ***/
		if(isset($_GET ['r'])){
			$remiact= $_GET ['r'];	
		}
		
		
		function oprimio($mysqli,$remiact){
	//esta funcion hace las consultas de actualizacion cuando se ha oprimido el boton
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
		$remi2 = $_POST ['rem'];

		
//actualizacion de la tabla de remisiones		
		
		$sqlCommand = "UPDATE $table SET fecha='$fecha', idremitido = '$idcliente', usu = '$usu', status = 4,cliente='$cliente',
		sucursal = '$sucursal',agente = '$agente', doctor = '$doctor',procedimiento = '$procedimiento',paciente = '$paciente', 
		registro = '$registro', subtotal =$subtotal,iva= $iva, total = $total, con_letra='$intotletra',tiporem= 0, custodio = NULL
		WHERE idremisiones = '$remi2' LIMIT 1";
			
	    // Execute the query here now
	    $query=mysqli_query($mysqli, $sqlCommand) or die ("error en la actualizacion".mysqli_error($mysqli)); 
//insercion en la tabla de artremisiones--------------------------------------------------------

		//obtencion de valores del html 
			$table = 'artremision';
			$codigo=  $_POST ['cod0'];
			$precio = $_POST ['inprecio0'];
			$cantidad = $_POST ['cant0'];
			$importe= $_POST ['inimpor0'];
			$usu = $_SESSION['login_user'];
			$sqlCommand= "INSERT INTO $table (codigo,remision,precio_unitario,cantidad,importe)
	    	VALUES ('$codigo',$remi2,$precio,$cantidad,$importe)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("error en insercion articulos ".mysqli_error($mysqli)); 
//insercion en la tabla de inventarios	    	
	   		//obtencion de valores
	   		$idproductos= $_POST ['inidprod0'];
			//construccion del numero de almacen
			if($sucursal==""){
				$almacen = $idcliente.'0';	
			}else{
				$almacen = nalmac($idcliente,$idsuccliente);
			}
		
	   //disminucion de almacen central   		
	   		$table = 'inventarios';
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',2000,2,-$cantidad,$remi2,'$usu',1)";
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN DIS. INV. CENTRAL ".mysqli_error($mysqli)); 
	   //Incremento en almacen de destino
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',$almacen,1,$cantidad,$remi2,'$usu',1)";
	    	$query=mysqli_query($mysqli, $sqlCommand) or die ("ERROR EN AUMENTO INV. CLIENTE ".mysqli_error($mysqli)); 
	}
	
	
		if(isset($_POST['enviorem'])){
			
		    oprimio($mysqli,$remiact);
			
			/* cerrar la conexion */
	    	mysqli_close($mysqli);  
			
		    // redirecci�n a la hoja pdf mediante javascript
		    echo '<script type="text/javascript" language="Javascript">
		    			window.open("consblanco.php","_self");  
                  </script>'; 
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
		$('#cliente').focus(); 
	    $('#cliente').autocomplete({
			autoFocus: true,
            source: "get_client_list.php",
            minLength: 2,
            select: function( event, ui ) {
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
 							$("#idrepresentantes").val( ui.item.idrepresentantes );
 							$("#agente").val( ui.item.label);
 							var trans1 = $("#razon").val();
 							var trans2 = $("#sucursal").val();
 							var trans3 = $("#rfc").val();
 							var trans4 = $("#fecha").val();
 							var trans5 = $("#agente").val();
 							var trans6 = $("#domicilio").val();
 							var trans7 = $("#idclientes").val();
            				$("#clientprint").append(" "+trans1);
            				$("#sucurprint").append(" "+trans2);
            				$("#rfcprint").append(" "+trans3);
            				$("#fechaprint").append(" "+trans4);
            				$("#agentprint").append(" "+trans5);
            				$("#domiprint").append(" "+trans6);	
            				$("#doctor").focus();
            						
            }  
        });
        
        $('#cod0').autocomplete({
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
        		var nivel = $("#nivel").val();
  //si el cliente es grupo angeles, se le agrega el ALG
        		if(cliente == 2){
        		$("#des0").append(des0+" ALG-"+alg)	
        		}else{ $("#des0").append(des0)
        				$("#indes0").val(des0);
        			}
        		$.getJSON("php/get_precio.php", {idproductos: idproducto , nivel: nivel}, function(data){
        			var precio1 = data[0].precio;
        			var preciof = $.number(precio1,2);
	   				$("#precio0").append(preciof);
	        		$("#inprecio0").val(precio1);
				});
        		$("#inidprod0").val(idproducto);
        		$("#cant0").focus();
        	}

        })
        
        $( "#cant0" ).change(function() {
        	var precio = $("#inprecio0").val();
        	var cant = parseInt(this.value);
			var importe = precio* cant;
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
    	  
        
	});
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "REMISIONES CLIENTES";
  include_once "include/barrasup.php";
  ?> 
 

<br />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	 <div class = "ui-widget-header">
	 	<legend>Datos de la Remisi�n:</legend>
	 	<label for="cliente">Cliente: </label>
	 	<input type="text" id="cliente"  name="cliente" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="rem" name = "rem" value = "<?php echo $remiact ?>"/>
	 	<input type="hidden" id="razon" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="idclientes" name="idclientes"/>
	 	<input type="hidden" id="rfc" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="domicilio" class="ui-autocomplete-content" length= "200"/>
	 	<input type="hidden" id="nivel" class="ui-autocomplete-content"/>
	 	<label for="sucursal">Sucursal: </label>
	 	<td><input type="text" id="sucursal"  name="sucursal" class="ui-autocomplete-content"/></td> 
	 	<input type="hidden" id="idsuccliente" name="idsuccliente"/>
	 	<label for="fecha">Fecha: </label>
	 	<td><input type="text" id="fecha"  name="fecha"/></td>   
	 	<label for="agente">Agente: </label>
	 	<td><input type="text" id="agente"  name="agente" class="ui-autocomplete-content"/></td>
	 	<input type="hidden" id="idrepresentantes" name="idrepresentantes"/>  
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
		<th>C�digo</th><th>Descripci�n</th><th>Precio Unitario</th><th>Cantidad</th><th>Importe</th>
		<?php
		for($i=0;$i<13;$i++){
			echo"<tr>
				<td class='ui-autocomplete-content' class='art'><input type='text' id='cod$i' name ='cod$i' /></td>
				<td id='des$i'></td><input type='hidden' id='indes$i' name ='indes$i' /><input type='hidden' id='inidprod$i' name ='inidprod$i' />
				<td id= 'precio$i'></td><input type='hidden' id='inprecio$i' name ='inprecio$i' />
				<td class='ui-autocomplete-content' class='art'><input type='number' id='cant$i' name='cant$i'/></td>
				<td  id = 'impor$i'></td><input type='hidden' id='inimpor$i' name ='inimpor$i' />
			</tr>";	
		}
		
		?>
		<tr><td colspan="3"></td><td class="coloridachic">SUBTOTAL</td><td id="subtotal"></td><input type="hidden" name = "insubtotal" id="insubtotal"/></tr>
		<tr><td colspan="3"></td><td class="coloridachic">I.V.A.</td><td id="iva"></td><input type="hidden" name = "iniva" id="iniva"/></tr>
		<tr><td class="coloridachic">TOTAL CON LETRA</td><td colspan="2" id="totletra"></td><input type="hidden" name = "intotletra" id="intotletra"/>
		<td class="coloridachic">TOTAL</td><td id="total"></td><input type="hidden" name = "intotal" id="intotal"/></tr>
		
	</table>
	
</div>
<p></p>
<div class="centraelem"><input type="submit" name ="enviorem" value="ACTUALIZA REMISION"/></div>
</form>
<div id="footer"></div>


</body>
