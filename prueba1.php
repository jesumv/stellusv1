<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="sp">
<head>
<meta charset="ISO-8859-1">

<title>PRUEBA 1</title>
<script type="text/javascript" src="js/funaux.js">	</script>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="js/jquery.number.js">	</script>

<script>
$(document).ready(function(){
	           
        	var cantidad = 26796;
        	var cantidadi = cantidad;
        	var cantidadf=$.number(cantidad,2);
        	$("#cant").append(cantidadf);
        	var cent = cantidadf.split("."); 
        	var ent = cent[0];
        	$("#ent").append(ent);
        	var cents = cent[1];
        	$("#cents").append(cents);
        	var millones = ObtenerParteEntDiv(cantidad, 1000000)
        	$("#mill").append(millones);
        	cantidad = mod(cantidad, 1000000)
        	$("#resmill").append(cantidad);
        	var miles = ObtenerParteEntDiv(cantidad, 1000)
        	$("#mil").append(miles);
        	cantidad = mod(cantidad, 1000)
			$("#resmil").append(cantidad);
			var centenas = ObtenerParteEntDiv(cantidad, 100)
        	$("#centena").append(centenas);
        	cantidad = mod(cantidad, 100)
			$("#rescentena").append(cantidad);
			var decenas = ObtenerParteEntDiv(cantidad, 10)
        	$("#decena").append(decenas);
        	cantidad = mod(cantidad, 10)
			$("#resdecena").append(cantidad);
			var unidades=  ObtenerParteEntDiv(cantidad, 1)
        	$("#unid").append(unidades);
        	cantidad = mod(cantidad, 1)
			$("#resunid").append(cantidad);
			
			var total = (cantidadi).toString();
			var totletra = covertirNumLetras(total);
			$("#totletra").append(totletra);
	

});
	
	
</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

 

<br />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
 	
<div>
	<table border="1">
		<th>CANTIDAD</th><th>ENTEROS</th><th>CENTAVOS</th><th>RESIDUO</th>
			<tr>
				<td id="cant"></td><td id="ent"></td><td id="cents"></td><td></td>
			</tr>
			
			<tr>
				<td>MILLONES</td><td id="mill"></td><td></td><td id="resmill"></td>
			</tr>	
			<tr>
				<td>MILES</td><td id="mil"></td><td></td><td id="resmil"></td>
			</tr>
			<tr>
				<td>CENTENAS</td><td id="centena"></td><td></td><td id="rescentena"></td>
			</tr>
				<td>DECENAS</td><td id="decena"></td><td></td><td id="resdecena"></td>
			</tr>
			</tr>
				<td>UNIDADES</td><td id="unid"></td><td></td><td id="resunid"></td>
			</tr>

		<tr><td>TOTAL CON LETRA</td><td colspan="5" id="totletra"></td>
		
	</table>
	
</div>
<p></p>
<div class="centraelem"><input type="submit" name ="enviorem" value="IMPRIMIR REMISION"/></div>
</form>
<div id="footer"></div>


</body>