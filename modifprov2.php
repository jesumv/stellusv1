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
	$table = 'proveedores';
     $nombre =strtoupper($_POST ['nom']) ;
     $corto =strtoupper($_POST ['corto']) ;
     $dir=strtoupper($_POST ['dir']) ;
     $usu = $_SESSION['username'];

	 
    if (!is_numeric($numid)) {
        $sqlCommand= "INSERT INTO $table (razon_social,nom_corto,direccion,usu,status)
        VALUES ('$nombre','$corto','$dir','$usu',0)"
        or die('insercion cancelada '.$table);
		
    }else {
        $sqlCommand = "UPDATE $table SET razon_social ='$nombre', nom_corto='$corto',
         direccion = '$dir', usu = '$usu',status = 1 WHERE idproveedores= $numid LIMIT 1"
         or die('actualizacion cancelada ');
    }
    // Execute the query here now
    $query = mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
    /* liberar la serie de resultados */
    mysqli_free_result($query);
    /* cerrar la conexion */
    mysqli_close($mysqli);  
}
/***si se oprimio el boton de accion***/

if(isset($_POST['enviomod'])){
	$numero = strtoupper($_POST ['num']) ;
    oprimio($mysqli, $numero);
    header('Location: proveedores.php');
}


/***obtiene los datos de acuerdo con el parametro recibido en la pagina***/
        if(isset($_GET['nid'])){
 
            if($_GET['nid']== -99){
                $num = "";
                $nombre = "";
                $corto = "";
                $dir= "";
                $titulo = "ALTA DE PROVEEDORES";
                //titulo del boton de la forma
                $titbot = "Insertar";
                
            }else{
                $num=$_GET['nid'];
                $sqlsresul= $funcbase->leetodos($mysqli,'proveedores','idproveedores= '."$num.");
                $num = $sqlsresul[0];
                $nombre = $sqlsresul[1];
                $corto = $sqlsresul[2];
                $dir= $sqlsresul[3];
                $titulo = "ACTUALIZACION PROVEEDORES";
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
<link rel="stylesheet" type="text/CSS" href="css/plantilla2.css" />
<link rel="stylesheet" type="text/CSS" href="css/dropdown_two.css" />
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/validaciones.js"></script>
<script src="js/additional-methods.js"></script>    
<title>STELLUS MEDEVICES</title>

  <script>
  $( document ).ready(function() {
       $('#inic').focus();
       validaforma();  
});
  </script>
</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
        include_once "include/barrasup.php";
  ?> 
  <div class = "centraelem">
        <h4>Los campos marcados con <span class="req">*</span>  son requeridos</h4>
  </div>
  
  <div class="cajacentra">

    <form id="modifprov" action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST">
        
        <div class="error" style="display:none;">
            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
            <span ></span><br clear="all" />
        </div>


       <table  class="db-table">
          
        <!-- la forma. ------>
        
             <tr>
                    <td >No.</td> 
                    <?php 
                    echo "<td><input type='hidden' id='num' name ='num' value = $num size = '60'/> </td>";
                    echo "<td>$num</td>";
                    echo "<td ><label for 'inic'>NOMBRE O RAZON SOCIAL:</label></td> ";
                    echo "<td class='field'><input id='inic' name ='nom' value = '$nombre'  size = '60'  class = 'requer'/>
                    <span class='req'>*</span></td>";
                    echo " <td><label for 'corto'> NOMBRE CORTO </label></td>";
                    echo "<td class='field'><input name ='corto' id='corto'value = '$corto' class = 'requer' />
                    <span class='req'>*</span></td>";
                    echo "<td> <label for 'corto'>DIRECCION</label></td>";
                    echo "<td class='field'><input name ='dir' id='dir'value = '$dir' size = '60' class = 'requer' />
                    <span class='req'>*</span></td>";
                    ?>         
             </tr>
   
     
                      
          </table>  <br />
    <!--------el boton de enviar ------------->
            <div class="centraelem">
                <?php
                   echo  "<input type='submit' name ='enviomod' value=$titbot />"
                ?>
            </div>        
        </form>
    

</div>

<div id="footer"></div>


</body>


</html>