<?php
global $num;
//este script administra la actualizacion de un proveedor
//conectar
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
		
        
function oprimio($mysqli,$numid){
//esta funcion hace las consultas de actualizacion
	$table = 'succliente';
	$cliente = strtoupper($_POST ['numcte']) ;
    $num_suc = strtoupper($_POST ['num_suc']) ;
    $nombre = strtoupper($_POST ['nom']) ;
    $almac= strtoupper($_POST ['inalmac']) ;
    $usu = $_SESSION['login_user'];

	 
    if (!is_numeric($numid)) {
        $sqlCommand= "INSERT INTO $table (cliente,no_suc,no_almacen,nom_sucursal,usu,status)
        VALUES ('$cliente','$num_suc','$almac','$nombre','$usu',0)"
        or die('insercion cancelada '.$table);
		
    }else {
        $sqlCommand = "UPDATE $table SET cliente ='$cliente', no_suc='$num_suc',
         no_almacen = '$almac', usu = '$usu',status = 1 WHERE idduccliente= $numid LIMIT 1"
         or die('actualizacion cancelada ');
    }
    // Execute the query here now
    $query = mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
    /* liberar la seri
	 * e de resultados */
    mysqli_free_result($query);
    /* cerrar la conexion */
    mysqli_close($mysqli);  
}
/***si se oprimio el boton de accion***/

if(isset($_POST['enviosuc'])){
	$numero = strtoupper($_POST ['num']) ;
    oprimio($mysqli, $numero);
    header('Location: include/otrasucdialog.xhtml');
}


/***obtiene los datos de acuerdo con el parametro recibido en la pagina***/
        if(isset($_GET['nid'])){
 
            if($_GET['nid']== -99){
            	//se va a insertar una nueva sucursal en blanco
            	$idsuccliente="";
            	$cliente = "";
                $no_suc = "";
                $no_almacen ="";
                $nom_sucursal = "";
                //titulo del boton de la forma
                $titbot = "Insertar";
                
            }elseif($_GET['nid']== -999){
            	//viene de la hoja de alta de cliente
            	$idsuccliente="";			
			//traer el numero de cliente recientemente insertado
			/***lee el numero de remision ***/
				$cliente= $funcbase->ultcliente($mysqli);
				$no_suc = "";
                $no_almacen ="";
                $nom_sucursal = "";
                //titulo del boton de la forma
                $titbot = "Insertar";
				
            }else{
            	//se va a modificar un cliente
            	
                $idsuccliente=$_GET['nid'];
                $sqlsresul= $funcbase->leetodos($mysqli,'succliente','idsuccliente= '."$idsuccliente.");
				$cliente = $sqlsresul[1];
                $no_suc = $sqlsresul[2];
                $no_almacen = $sqlsresul[3];
                $nom_sucursal= $sqlsresul[4];
                $titbot = "Actualizar";
                
            }
    
        }else{
        die("<h1>NO HAY DATOS PARA CONSTRUIR LA PAGINA</h1>");
        }
    
    } else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
   

?>
<!--construccion de la pagina--->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<link rel="stylesheet" href="css/cupertino/jquery-ui-1.10.4.custom.css">
<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>

<title>STELLUS MEDEVICES</title>

  <script>
  $( document ).ready(function() {
       $('#inic').focus();
 //si es alta, se hace autocomplete el input de cliente
       <?php
	       	if($_GET['nid']== -99){
	       	echo "var cliente;
	       	$('#cliente').autocomplete({
				autoFocus: true,
	            source: 'get_client_list.php',
	            minLength: 2,
	            select: function( event, ui ) {
	 							$('#cliente').val( ui.item.label );
	            				$('#numcte').val( ui.item.idclientes);	            				
								idclientes = ui.item.idclientes;
				//obtener el numero de almacen m�s alto para ese cliente		        
			        $.getJSON('php/get_maxalmac.php', {cliente: idclientes }, function(data){
	        			var almax = parseInt(data[0].no_max)+parseInt(1);
				//si es la primera sucursal, el valor devuelto es NaN, por lo que se modifica con codigo
						if(isNaN(almax)){almax=1}
						var nalm = idclientes+almax;
						$('#num_suc').val(almax);
		        		$('#almac').val(nalm);
						$('#inalmac').val(nalm);
					});
		            } 

		        });";
				
	       	}elseif(($_GET['nid']== -999)){
	       		echo " 
	       				var idclientes =$('#numcte').val();
			       		$.getJSON('php/get_maxalmac.php', {cliente: idclientes }, function(data){
			        			var almax = parseInt(data[0].no_max)+parseInt(1);
						//si es la primera sucursal, el valor devuelto es NaN, por lo que se modifica con codigo
								if(isNaN(almax)){almax=1}
								var nalm = idclientes+almax;
								$('#num_suc').val(almax);
				        		$('#almac').val(nalm);
								$('#inalmac').val(nalm);
						});";
	       		 
	       	}
       
       	?>  
});
  </script>
</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "ACTUALIZACION SUCURSALES";
  include_once "include/barrasup.php";
  
  ?> 
  
  <div class="cajacentra">

    <form id="modifsuc" action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
        
       <table  class="db-table">
          
        <!-- la forma. ------>
        
     <tr>
            <td >No.</td> 
            <?php 
            echo "<input type='hidden' id='num' name ='num' value = $idsuccliente size = '60'/>";
            echo "<td>$idsuccliente</td>";
			echo "<input type='hidden' id='num_suc' name ='num_suc' size = '60'/>";
            echo "<td >NOMBRE SUCURSAL:</td> ";
            echo "<td><input id='inic' name ='nom' value = '$nom_sucursal'  size = '60'/> </td>";
            echo " <td> CLIENTE</td>";
            echo "<td ><input name ='cliente' id= 'cliente' value = '$cliente' /></td>";
			echo "<input type='hidden' id='numcte' name ='numcte' value = '$cliente' size = '60'/>";
            echo "<td> NO. ALMACEN</td>";
            echo "<td ><input name ='almac' id='almac' value = '$no_almacen' size = '60' disabled/></td>";
			echo "<td ><input type = 'hidden' name ='inalmac' id='inalmac' value = '$no_almacen' size = '60' /></td>";
            ?>         
     </tr>
   
     
                      
          </table>  <br />
    <!--------el boton de enviar ------------->
    <div class="centraelem">
        <?php
           echo  "<input type='submit' name ='enviosuc' value=$titbot />"
        ?>
    </div>        
        </form>
    

</div>

<div id="footer"></div>


</body>


</html>