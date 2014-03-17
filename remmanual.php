<?php
	global $remiact;
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
		
		function oprimio($mysqli,$remiact){
	//esta funcion hace las consultas de actualizacion
		$table = 'remisiones';
	// LAS REMISIONES EN BLANCO VAN AL CLIENTE 2 OTROS
	    $idcliente = 2 ;
		$fecha =strtoupper($_POST ['fecha']) ;
	    $usu = $_SESSION['login_user'];
		$cliente = $_POST ['cliente'];
		$sucursal = $_POST ['sucursal'];
		$agente = $_POST ['idrepresentantes'];
		$doctor= $_POST ['doctor'];
		$procedimiento = $_POST ['procedimiento'];
		$paciente = $_POST ['paciente'];
		$registro = $_POST ['registro'];
		$subtotal =  $_POST ['insubtotal'];
		$iva =  $_POST ['iniva'];
		$total =  $_POST ['intotal'];
		$intotletra = $_POST ['intotletra'];
		$domicilio = strtoupper($_POST ['domicilio']);
		$rfc = strtoupper($_POST ['rfc']);
		
//insercion en la tabla de remisiones		
	    $sqlCommand= "INSERT INTO $table (idremisiones,fecha,idremitido,usu,status,cliente,sucursal,agente,doctor,procedimiento,
	    paciente,registro,subtotal,iva,total,con_letra,tiporem,domicilio,rfc)
	    VALUES ($remiact,'$fecha','$idcliente','$usu',0,'$cliente','$sucursal',$agente,'$doctor','$procedimiento','$paciente','$registro',
	    $subtotal,$iva,$total,'$intotletra',0,'$domicilio','$rfc')"
	    or die('insercion cancelada '.$table);
			
	    // Execute the query here now
	    $query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
//insercion en la tabla de artremisiones--------------------------------------------------------

		//obtencion de valores del html 
			$table = 'artremision';
			$codigo=  $_POST ['cod0'];
			$precio = $_POST ['inprecio0'];
			$cantidad = $_POST ['cant0'];
			$importe= $_POST ['inimpor0'];
			$usu = $_SESSION['login_user'];
			$sqlCommand= "INSERT INTO $table (codigo,remision,precio_unitario,cantidad,importe)
	    	VALUES ('$codigo',$remiact,$precio,$cantidad,$importe)"
	    	or die('insercion cancelada '.$table);
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
//insercion en la tabla de inventarios	    	
	   		//obtencion de valores
	   		$idproductos= $_POST ['inidprod0'];
			//construccion del numero de almacen. en remisiones manuales siempre es 0
				$almacen = $idcliente.'0';	
		
	   //disminucion de almacen central   		
	   		$table = 'inventarios';
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',2000,2,-$cantidad,$remiact,'$usu',1)"
	    	or die('insercion cancelada '.$table);
			// Execute the query here now
	    	$query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
	   //Incremento en almacen de destino
	   		$sqlCommand= "INSERT INTO $table (idproductos,fecha,almacen,tipomov,cantidad,referencia,usu,status)
	    	VALUES ($idproductos,'$fecha',$almacen,1,$cantidad,$remiact,'$usu',1)"
	    	or die('insercion cancelada '.$table);
	    	$query=mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
	}
	
	
		if(isset($_POST['enviorem'])){
			
		    oprimio($mysqli,$remiact);
			
			/* cerrar la conexion */
	    	mysqli_close($mysqli);  
			
		    // redirección a la hoja pdf mediante javascript
		    echo '<script type="text/javascript" language="Javascript">
		    			window.open("php/rremmanual.php?r='.$remiact.'");  
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
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="js/funaux.js">	</script>
<script type="text/javascript" src="js/jquery.number.js">	</script>

<script>
	$(function(){
		$('#cliente').focus();  
		$('#cliente').change(function(){
			$('#sucursal').focus();
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
            	$("#domicilio").focus();
            						
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
        		var idproducto =  ui.item.idproductos;
        		$("#inidprod0").val(idproducto);
        		$("#indes0").val(des0);
        		$("#inprecio0").focus();
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
  $titulo = "REMISIONES MANUALES";
  include_once "include/barrasup.php";
  ?> 
 

<br />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">

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
				<td id="clientprint"><b>CLIENTE:</b>:</td>
				<td><input type="text"  id="cliente" name = "cliente" size="60"/></td>
				<td id="sucurprint"><b>SUCURSAL:</b></td>  
				<td><input type="text"  id="sucursal" name = "sucursal" size="30"/></td>            
				<td id="rfcprint"><b>RFC:</b></td>
				<td><input type="text"  id="rfc" name = "rfc" size="13"/></td> 				
			</tr>
			<tr>	
				<td id="fechaprint"><b>FECHA:</b></td>
				<td><input type="text"  id="fecha" name = "fecha" size="15"/></td> 	
				<td id="agentprint"><b>AGENTE:</b></td>
				<td colspan="3"><input type="text"  id="agente" name = "agente" size="30"/></td> 
				<input type "hidden"  id="idrepresentantes" name="idrepresentantes"/>	
			</tr>
			<tr><td  id="domiprint"><b>DOMICILIO:</b></td>
				<td colspan="6"><input type="text"  id="domicilio" name = "domicilio" size="180"/></td> 
			</tr>
				
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
				<td class='ui-autocomplete-content' class='art'><input type='text' id='cod$i' name ='cod$i' /></td>
				<td id='des$i'><input type='text' id='indes$i' name ='indes$i' size='60' disabled /></td><input type='hidden' id='inidprod$i' name ='inidprod$i' />
				<td id= 'precio$i'><input type='text' id='inprecio$i' name ='inprecio$i' /></td>
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
<div class="centraelem"><input type="submit" name ="enviorem" value="IMPRIMIR REMISION"/></div>
</form>
<div id="footer"></div>


</body>
