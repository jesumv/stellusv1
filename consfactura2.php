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
    
?>

<!DOCTYPE html>
<html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
	
	<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.css">
	<link rel="stylesheet" type="text/CSS" href="css/plantilla3.css" />
	<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
	<link rel="shortcut icon" href="img/logomin.gif" />
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	
	<title>STELLUS MEDEVICES</title>
	
	<script>
		var artsfact=[];
		var cantsfact=[];
	//funciones de llenado de elementos
	function llenasinselec(){
//se inicializa array

		//esta funcion llena las columnas de producto
//llamada ajax
			$.get("php/getartsfact.php",
    function(data, status){
//se añaden las columnas de productos;
		 obj = JSON.parse(data);
		 var tr = document.getElementById('matriz').tHead.children[0];
		for(i=0; i < obj.length; i++) {
    		th = document.createElement('th');
			th.innerHTML = obj[i].descrip;
			artsfact[i]=obj[i].codigo;
			cantsfact[i]=obj[i].codigo;
			cantsfact[i]=obj[i].cant;
			th.className ='vtext';
			tr.appendChild(th);
		};
//Se añaden las demas columnas
			th = document.createElement('th');
			th.innerHTML = 'SUBTOTAL';
			tr.appendChild(th);
			th = document.createElement('th');
			th.innerHTML = 'IVA';
			tr.appendChild(th);
			th = document.createElement('th');
			th.innerHTML = 'TOTAL';
			tr.appendChild(th);
			th = document.createElement('th');
			th.innerHTML = 'OBSERVACIONES';
			tr.appendChild(th);
			th = document.createElement('th');
			th.innerHTML = 'STATUS';
			tr.appendChild(th);
		
    	}
			
	);
//llenado de renglones

		$.get('php/getfacts.php',function(data){
			var table = document.getElementById("matriz");
			obj = JSON.parse(data);
			var fact;
			for( z=0; z <obj.length; z++) {
//insercion de renglon	
				var row = table.insertRow(z+1);
//extraccion de datos del array
				fact = obj[z].fact;
				var rem = obj[z].rem;
				var oc = obj[z].oc;
				var remoco = remoc(rem,oc)
				var fecha = obj[z].fecha;
				function nomcli(cte,suc,haysuc){if(haysuc==-1){nomcte = cte}else{nomcte = cte+' suc. '+ suc}
							return nomcte;};
				var cliente = nomcli(obj[z].cte,obj[z].suc,obj[z].haysuc)
				var agente = obj[z].ag;
				
//adicion de celdas iniciales
			  	var cell0 = row.insertCell(0);
				cell0.innerHTML = fact;
				var cell1 = row.insertCell(1);
				cell1.innerHTML = remoco;
				var cell2 = row.insertCell(2);
				cell2.innerHTML = fecha;
				var cell3 = row.insertCell(3);
				cell3.innerHTML = cliente;
				var cell4 = row.insertCell(4);
				cell4.innerHTML = agente;
//adicion de cantidades
				for(y=0; y<cantsfact.length;y++){
						var prod=cantsfact[y];
						var reng =artsfact[y]
						alert(prod+reng+y)
						if(prod==reng){
						var celli = row.insertCell(5+y);
						celli.innerHTML = prod;
						};
						
					};
		
						
		};
		});
		
	}
	

	$(document).ready(function() {
//llenado de elementos
		llenasinselec();
		
//funciones de jquery ui	
	//boton para exportar la tabla	
     $(".botonExcel").click(function(event) {
     $("#datos_a_enviar").val( $("<div>").append( $("#tablaaxl").eq(0).clone()).html());
     $("#FormularioExportacion").submit();
		});
		


			
	});
	function remoc(rem,oc){
//esta funcion construye el elemento rem/oc de la tabla de facturas
		switch(rem){
			case 0:
				switch(oc){
					case 0:
						comb=""
						break;
					default:'o.c. '+oc
				}
			break;
			default:
			if(oc!=0){comb='rem. '+rem+'/'+'o.c. '+oc;}else{comb='rem. '+rem};
		}
	return comb;
	}
	
	function nomcte(cte,suc,haysuc){
					//esta funcion construye el nombre del cliente para la tabla de facturas
							if(haysuc==-1){nomcte = cte}else{nomcte = cte+' suc. '+ suc}
							return nomcte;
	};
	

</script>
</head>	

<body>

	<header>
  		<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
	  $titulo = "CONSULTA DE FACTURACION";
	  include_once "include/barrasup.php";
	 //------consultas a la base de datos------
	  
 	?>
	</header>
<section id="cajaelec">
	
</section>
  
  <section>
  	<table id='matriz'>
  		<thead>
  			<th>FACTURA</th>
  			<th>REMISION/ORDEN DE COMPRA</th>
  			<th>FECHA</th>
  			<th>CLIENTE</th>
  			<th id='ancla'>AGENTE</th>

  		</thead>
  		<tbody></tbody>
  	</table>
  	
  </section>


</body>

</html>


