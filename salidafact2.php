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
<link rel="stylesheet" type="text/CSS" href="css/formstyle.css" />
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="js/funaux.js">	</script>
<script src="js/jquery.validate.js"></script>
<script src="js/validaciones.js"></script>
<script src="js/additional-methods.js"></script>
<script src="js/jquery.form.js"></script>
<script>
	$(document).ready(function() {
//se oculta la forma de captura 
	 document.getElementById("capauto").style.display = "none";
	var options = { 
			target:   '#output',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
			uploadProgress: OnProgress, //upload progress callback 
			resetForm: true ,      // reset the form after successful submit 
			dataType: 'json'
		}; 
		
	 $('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(options);  			
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		}); 
		

//function after succesful file upload (when server response)
function afterSuccess($results)
{
	$('#submit-btn').show(); //hide submit button
	$('#loading-img').hide(); //hide submit button
	$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar
	document.getElementById("upload-wrapper").style.display = "none";
//mostrar la forma de captura
	document.getElementById("capauto").style.display = "block";
//traer los datos del archivo de la factura	
	var fact = $results[0]['nofact'];
	var razon = $results[0]['razon'];
	var fecha =fechast($results[0]['fecha']);
	var subt = $results[0]['subt'];
	var iva = $results[0]['imp'];
	var tot = $results[0]['total'];
	var arts = $results[0]['arts'];
//validaciones
	//la factura no existe
	//el cliente existe
	
//datos adicionales
	//el numero de cliente
	var idcliente = getResponse('get_idcliente.php', razon);
	
	//escribir los datos de la factura
	$('#razon').val(razon);
	$('#fecha').val(fecha);
	$('#fact').val(fact);
	anadetabla(subt,iva, tot);
	for (i= 0; i < arts; i++){
		var cod =  $results[0]['clave'.concat(i)];
		var descrip = $results[0]['desc'.concat(i)];
		var cantid = $results[0]['cant'.concat(i)];
		var punit = $results[0]['punit'.concat(i)];
		var impor = $results[0]['impor'.concat(i)];
		anadefila(cod,descrip,cantid,punit,impor)
	}
	
	$('#sucursal').focus();
	
/*seccion de autocomplete jqueryui */
	$('#sucursal').autocomplete({
			autoFocus: true,
            source: function(request, response) {
    		$.getJSON("get_sucur_list2p.php", { term: $('#sucursal').val(), cte: $(idcliente).val() }, 
              response);
  				},
            minLength: 2,
            select:function(event, ui){
            	$("#idsuccliente").val(ui.item.noalmacen);
            	$('#agente').focus();
            }			                
        });

//function to check file size before uploading.

  		
};

function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#FileInput').val()) //check empty input filed
		{
			$("#output").html("POR FAVOR SELECCIONE UN ARCHIVO");
			return false
		}
		
		var fsize = $('#FileInput')[0].files[0].size; //get file size
		var ftype = $('#FileInput')[0].files[0].type; // get file type
		

		//allow file types 
		switch(ftype)
        {
 
			case 'text/xml':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> NO ES UN TIPO DE ARCHIVO PERMITIDO!");
				return false
        }
		
		//Allowed file size is less than 1 MB (1048576)
		if(fsize>1048576) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
			return false
		}
				
		$('#submit-btn').hide(); //hide submit button
		$('#loading-img').show(); //hide submit button
		$("#output").html("");  
	}
	else
	{
		//Output error to older unsupported browsers that doesn't support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
	$('#progressbox').show();
    $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
    $('#statustxt').html(percentComplete + '%'); //update status text
    if(percentComplete>50)
        {
            $('#statustxt').css('color','#000'); //change status text to white after 50%
        }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

//funcion para añadir tabla de articulos
function anadetabla(subt,iva,tot){
	//encabezado
	var tabla = document.getElementById("arts");
    var tr = tabla.appendChild(document.createElement('tr'));
    var th = tr.appendChild(document.createElement('th'));
    th.innerHTML = "CODIGO";
    var th2 = tr.appendChild(document.createElement('th'));
    th2.innerHTML = "DESCRIPCION";
     var th3 = tr.appendChild(document.createElement('th'));
    th3.innerHTML = "CANTIDAD";
     var th4 = tr.appendChild(document.createElement('th'));
    th4.innerHTML = "PRECIO UNITARIO";
     var th5= tr.appendChild(document.createElement('th'));
    th5.innerHTML = "IMPORTE";
    
    //pie de tabla
    var tabla2 = document.getElementById("pie");
    var tr6 = tabla2.appendChild(document.createElement('tr'));
    var th6 = tr6.appendChild(document.createElement('th'));
    th6.innerHTML = "SUBTOTAL";
    var th61 = tr6.appendChild(document.createElement('th'));
    th61.innerHTML = addCommas(subt);
    var tr7= tabla2.appendChild(document.createElement('tr'));
    var th7 = tr7.appendChild(document.createElement('th'));
    th7.innerHTML = "IVA";
    var th71 = tr7.appendChild(document.createElement('th'));
    th71.innerHTML = addCommas(iva);
    var tr8= tabla2.appendChild(document.createElement('tr'));
    var th8 = tr8.appendChild(document.createElement('th'));
    th8.innerHTML ="TOTAL";
    var th81 = tr8.appendChild(document.createElement('th'));
    th81.innerHTML = addCommas(tot);
}

//funcion para añadir renglones a tabla de articulos
function anadefila(codigo,descrip,cantid,punit,impor) { 
    var tabla = document.getElementById("arts");
    var fila = tabla.appendChild(document.createElement('tr'));
    var td = fila.appendChild(document.createElement('td'));
    td.innerHTML = codigo;
    var td2 = fila.appendChild(document.createElement('td'));
    td2.innerHTML = descrip;
     var td3 = fila.appendChild(document.createElement('td'));
    td3.innerHTML = quitadec(cantid);
     var td4 = fila.appendChild(document.createElement('td'));
    td4.innerHTML = addCommas(punit);
     var td5 = fila.appendChild(document.createElement('td'));
    td5.innerHTML = addCommas(impor);
}

});
		


</script>

</head>

<body>
<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  

    <?php 
  $titulo = "REGISTRO DE FACTURAS";
  include_once "include/barrasup.php";
  ?> 

<!-- CONSTRUCCION DE CAJA DE ELECCION DE ARCHIVO A SUBIR ------------------------------------->
  
<div id="upload-wrapper">
<div align="center">
	<h3>Seleccione el archivo Xml de la factura:</h3>
	<form action="php/auxfactura.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
	<input name="FileInput" id="FileInput" type="file" />
	<input type="submit"  id="submit-btn" value="Subir" />
	<img src="img/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
	</form>
	<div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
	<div id="output"></div>
</div>
	
	<div id="eleccion1" align="center">
		<h2>Ó:</h2>
		<br />
		<h3><input type="radio" name="manual" value="manual">Subir Factura Manual:</h3>
		</div>
		
</div>

<!-- forma para captura de datos ------------------------------>
	<div id="tablacomp">	
		<div id="capauto"> 
	  		<div class = "centraelem">
	    <h4>Los campos marcados con <span class="req">*</span>  son requeridos</h4>
	  	</div>
	  
	<br />
	 	<form id="salidafact" action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
	     
		      <div class="error" style="display:none;">
		            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
		            <span ></span><br clear="all" />
		      </div>
	        
			 <div class = "encabezadofact" id="encabezado">
			         <table>
			         <tr>
			         	<th>FACTURA</th> <th>FECHA</th><th>CLIENTE</th><th>SUCURSAL</th><th>AGENTE</th><th>REMISION</th><th>ORDEN DE COMPRA</th>
			         </tr>
		            <tr>
		            	<td class="field"><input type='text' id='fact' name ='fact' class="requer"/><span class='req'>*</span></td>
		            	<td class="field"><input type="text" id="fecha"  name="fecha" class="requer"/><span class='req'>*</span></td>
		                <td class="field"><input type="text" size="50" id="razon"  name="razon" class="requer"/><span class='req'>*</span></td>
		                <input type="hidden" id="idclientes" name="idclientes"/>
		                <td class="field"><input type="text" id="sucursal"  name="sucursal" class="ui-autocomplete-content"/></td>
		                <input type="hidden" id="idsuccliente" name="idsuccliente"/>
		                <td class="field"><input type="text" id="agente"  name="agente" class="requer"/><span class='req'>*</span></td>
		                <input type='hidden' id='idrepresentantes' name ='idrepresentantes' />
		                <td class="field"><input type='text' id='rem' name ='rem'  /><span class='req'>*</span></td>
		                <td class="field"><input type='text' id='oc' name ='oc' class="requer" /><span class='req'>*</span></td>                               
		            </tr>    
		 
		            <tr>
		                <td><label for="obser">Observaciones: </label></td>
		                <td class="field"colspan="4"><input type='text' id='obser' name ='obser' size='100'/></td>
		            </tr>      
		        </table>			  
			 </div> 
			 <div>
			 	<table id="arts"class="detallefact"></table>
			 </div>
			 <div>
			 	<table id="pie" class="piefact"></table>
			 </div>
			<p></p>
			<div class="centraelem"><input type="submit" name ="enviodato" value="ENVIAR DATOS"/></div>
		</form>
	</div> 

</div>

<div id="footer"></div>


</body>
