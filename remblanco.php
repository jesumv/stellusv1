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
		var numero = $( "#numero" ),
		asignadoa = $( "#asignadoa" );
		allFields = $( [] ).add( numero ).add( asignadoa );
		
		 $( "#dialog-form" ).dialog({
		autoOpen: true,
		height: 250,
		width: 250,
		modal: true,
		zIndex:10000,
		position: { my: "center", at: "center", of: "#remision" },
		buttons: {
			"ok": function() {
			allFields.removeClass( "ui-state-error" );
			var remiact = parseInt($("#remiact").val());
			var numremi = parseInt($("#numero").val());
			if(numremi==""){$("#numero").val(1);}
			var custodio = $("#asignadoa").val();
			var nuevonum = parseInt(remiact)+parseInt(numremi);
			$.ajax({
				type: 'POST',
				url: "php/send_remblanco.php",
				data: $('#datos').serialize(),
				success: function(response) {
					//esta funcion no esta funcionando
				//$("#numremi").replaceWith("<td class='contenido' colspan = 2 id='numremi' 'name='numremi'>N."+nuevonum+"</td>");
				alert(response);
				},
				dataType: 'json',
			});
				window.open("php/rremblanco.php?r="+remiact+"&c="+nuevonum);  
			$( this ).dialog( "close" );
			},
			Cancelar: function() {
			$( this ).dialog( "close" );
			}
		},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
			});
			
			$('#asignadoa').autocomplete({
			autoFocus: true,
            source: "get_agent_list.php",
            minLength: 2,
            select: function( event, ui ) {
 							$("#asignadoa").val( ui.item.label );
            						
            }  
        });
				
	})
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
	  $titulo = "REMISIONES EN BLANCO";
	  include_once "include/barrasup.php";
  ?> 
 
<br />


<!-- forma para captura de datos ------------------------------>
<div id="dialog-form" title="Datos para la remision" class="dialogo">
	<form action="#" method="post" name = "datos" id="datos">
		<fieldset class="cajita">
		<label for="numero" class="cajita">Número de Remisiones</label>
		<input type="text" name="numero" id="numero" value="" class="text ui-widget-content ui-corner-all" class="cajita">
		<label for="asignadoa" class="cajita">Asignadas a</label>
		<input type="text" name="asignadoa" id="asignadoa" class="text ui-widget-content ui-corner-all" class="cajita">
		<input type='hidden'  name="remiact" id="remiact" value= <?php echo $remiact?> />
		</fieldset>
	</form> 
</div>

<p></p>       

	<table id= "remision" class="tablap">
			<tr>
				<td id= "logo" rowspan="3" colspan = "5"><img src="img/logoremis.jpg" /></td>
				<td class="colorida" colspan = "2">REMISION</td>
			</tr>
			<tr>
				<td class="contenido" colspan = "2" id="numremi" name="numremi">N.<?php echo $remiact?></td>
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
		<tr><td class="coloridachic">DOCTOR</td><td><input type = "text" id="doctor" name = "doctor" size = "60" disabled/></td>
			<td class="coloridachic">PROCEDIMIENTO</td><td><input type = "text" id="procedimiento" name = "procedimiento" size = "60" disabled/></td>
		</tr>
		<tr><td class="coloridachic">PACIENTE</td><td><input type = "text" id="paciente" name ="paciente" size = "60" disabled/></td>
			<td class="coloridachic">N.REGISTRO</td><td><input type = "text" id="registro" name = "registro" size= "60" disabled/></td>
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
				<td class='ui-autocomplete-content' class='art'><input type='text' id='cod$i' name ='cod$i' disabled/></td>
				<td id='des$i'></td><input type='hidden' id='indes$i' name ='indes$i' /><input type='hidden' id='inidprod$i' name ='inidprod$i' />
				<td id= 'precio$i'></td><input type='hidden' id='inprecio$i' name ='inprecio$i' />
				<td class='ui-autocomplete-content' class='art'><input type='number' id='cant$i' name='cant$i' disabled/></td>
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
</form>
<div id="footer"><h2 name="respuesta" id="respuesta"></h2></div>


</body>
