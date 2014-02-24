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
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="js/funaux.js">	</script>

<script>
	$(function(){
		$('#cliente').focus(); 
		$( "#radio" ).buttonset();
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
				}
				                
        });
        $('#sucursal').autocomplete({
			autoFocus: true,
            source: "get_sucur_list.php",
            minLength: 2,			                
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
 							$("#agente").val( ui.item.label );
 							var trans1 = $("#razon").val();
 							var trans2 = $("#sucursal").val();
 							var trans3 = $("#rfc").val();
 							var trans4 = $("#fecha").val();
 							var trans5 = $("#agente").val();
 							var trans6 = $("#domicilio").val();
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
        		this.value = ui.item.codigo;
        		var des0 = ui.item.desc;
        		$("#des0").append(des0);
        		$("#precio0").val("8000");
        		$("#cant0").focus();
        	}

        })
        
        $( "#cant0" ).change(function() {
        	var precio = $("#precio0").val();
        	var cant = parseInt(this.value);
			var importe = precio* cant;
			$("#impor0").append(importe);
			$("#subtotal").append(importe);
			var iva = calciva(importe)	;
			$("#iva").append(iva);
			var total = importe+iva;
			$("#total").append(total);
			var totletra = total;
			$("#totletra").append(totletra);
		});
    	  
        
	});
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "REMISIONES";
  include_once "include/barrasup.php";
  ?> 
 
<div id="radio" class="centraelem">
	<legend>Tipo de Remisión</legend>
	<input type="radio" id="radio1" name="radio"  checked="checked"><label for="radio1">Cliente</label>
	<input type="radio" id="radio2" name="radio"><label for="radio2">Vendedor</label>
	<input type="radio" id="radio3" name="radio"><label for="radio3">Otra</label>
</div>

<br />
 <form>
	 <div class = "ui-widget-header">
	 	<legend>Datos de la Remisión:</legend>
	 	<label for="cliente">Cliente: </label>
	 	<input type="text" id="cliente"  name="cliente" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="razon" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="idclientes" />
	 	<input type="hidden" id="rfc" class="ui-autocomplete-content"/>
	 	<input type="hidden" id="domicilio" class="ui-autocomplete-content" length= "200"/>
	 	<input type="hidden" id="nivel" class="ui-autocomplete-content"/>
	 	<label for="sucursal">Sucursal: </label>
	 	<td><input type="text" id="sucursal"  name="sucursal" class="ui-autocomplete-content"/></td> 
	 	<label for="fecha">Fecha: </label>
	 	<td><input type="text" id="fecha"  name="fecha"/></td>   
	 	<label for="agente">Agente: </label>
	 	<td><input type="text" id="agente"  name="agente" class="ui-autocomplete-content"/></td>   
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
		<tr><td class="coloridachic">DOCTOR</td><td><input type = "text" id="doctor" size = "60"/></td>
			<td class="coloridachic">PROCEDIMIENTO</td><td><input type = "text" id="procedimiento" size = "60"/></td>
		</tr>
		<tr><td class="coloridachic">PACIENTE</td><td><input type = "text" id="paciemte" size = "60"/></td>
			<td class="coloridachic">N.REGISTRO</td><td><input type = "text" id="registro" size= "60"/></td>
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
				<td class='ui-autocomplete-content' class='art'><input type='text' id='cod$i'/></td>
				<td id='des$i'></td>
				<td class='ui-autocomplete-content' class='art'><input type='number' id='precio$i' disabled/></td>
				<td class='ui-autocomplete-content' class='art'><input type='number' id='cant$i'/></td>
				</td><td id='impor$i'></td>
			</tr>";	
		}
		
		?>
		<tr><td colspan="3"></td><td class="coloridachic">SUBTOTAL</td><td id="subtotal"></td></tr>
		<tr><td colspan="3"></td><td class="coloridachic">I.V.A.</td><td id="iva"></td></tr>
		<tr><td class="coloridachic">TOTAL CON LETRA</td><td colspan="2" id="totletra"><td class="coloridachic">TOTAL</td><td id="total"></td></tr>
		
	</table>
	
</div>
<p></p>
<div class="centraelem"><input type="submit" value="IMPRIMIR REMISION"/></div>
</form>
<div id="footer"></div>


</body>
