<?php
global $num;

//Este script administra lo actualizaci—n a un cliente.
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
		$table = 'representantes';
	    $paterno =strtoupper($_POST ['paterno']) ;
		$materno =strtoupper($_POST ['materno']) ;
		$nombre=strtoupper($_POST ['nombre']) ;
		$corto=strtoupper($_POST ['corto']) ;
	    $porccomision =strtoupper($_POST ['porccomision']) ;
	    $usu = $_SESSION['username'];

	 
	    if (!is_numeric($numid)) {
	        $sqlCommand= "INSERT INTO $table (paterno,materno,nombre,nom_corto,porccomision,usu,status)
	        VALUES ('$paterno','$materno','$nombre','$corto',$porccomision,'$usu',0)"
	        or die('insercion cancelada '.$table);
			
	    }else {
	        $sqlCommand = "UPDATE $table SET paterno ='$paterno', materno= '$materno', nombre='$nombre',nom_corto='$corto',
	        porccomision=$porccomision,usu = '$usu',status = 1 WHERE idrepresentantes= $numid LIMIT 1"
	         or die('actualizacion cancelada '.$table);
    	}
	    // Execute the query here now
	    $query = mysqli_query($mysqli, $sqlCommand) or die (mysqli_error($mysqli)); 
	    /* cerrar la conexion */
	    mysqli_close($mysqli);
		
	}
/***si se oprimio el boton de accion***/
if(isset($_POST['enviomod'])){
	$numero = strtoupper($_POST ['num']) ;	
    oprimio($mysqli, $numero);
    header('Location: representantes.php');
}
	
/***obtiene los datos de acuerdo con el parametro recibido en la pagina***/
        if(isset($_GET['nid'])){
            if($_GET['nid']== -99){
            	$num= "";
                $paterno= "";
				$materno = "";
                $nombre = "";
                $corto="";
                $porccomision="";
                $titulo = "ALTA DE REPRESENTANTES";
                //titulo del boton de la forma
                $titbot = "Insertar";
                
            }else{
                $num=$_GET['nid'];
                $sqlsresul= $funcbase->leetodos($mysqli,'representantes','idrepresentantes= '."$num.");
                $num = $sqlsresul[0];
				$paterno = $sqlsresul[1];
				$materno = $sqlsresul[2];
				$nombre= $sqlsresul[3];
				$corto= $sqlsresul[4];
				$porccomision = $sqlsresul[5];
				$titulo = "ACTUALIZACION REPRESENTANTES";
                $titbot = "Actualizar";
                
            }
    
        }else{
        die("<h1>NO HAY DATOS PARA CONSTRUIR LA PAGINA</h1>");
        }  	
		
	}else {
        die ("<h1>'No se establecio la conexion a bd'</h1>");
    }
?>

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
    <?php include_once "include/barrasup.php"; ?> 
    
    <div class = "centraelem">
        <h4>Los campos marcados con <span class="req">*</span>  son requeridos</h4>
    </div>

 <!-- la forma. ------>
  <div class="cajacentra">

    <form id="modifprod" action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
        
        <div class="error" style="display:none;">
            <img src="img/warning.gif" alt="Warning!" width="24" height="24" style="float:left; margin: -5px 10px 0px 0px; " />
            <span ></span><br clear="all" />
        </div>
        
        
       <table  class="db-table">
             
     <tr>
            <tr>
                <td >No.</td> 
                <?php
                echo "<input type='hidden' id='num' name ='num' value = $num />";
                echo "<td>$num</td>";
                echo "<td><label for 'inic'>Apellido Paterno</label></td>";
				echo "<td class='field'><input id='inic' name ='paterno' value = '$paterno'  size = '55' class = 'requer'/> 
				<span class='req'>*</span> </td>";
				echo "<td><label for 'materno'>Apellido Materno</label></td>";
				echo "<td class='field'><input name ='materno' value = '$materno'  size = '55' class = 'requer'/> 
				<span class='req'>*</span></td>";
     echo "</tr>";
            echo "<tr>";
				echo "<td ><label for 'nombre'>Nombre</label></td> ";
                echo "<td class='field'><input  name ='nombre' value = '$nombre'  size = '55' class = 'requer'/> 
                <span class='req'>*</span></td>";
                echo "<td ><label for 'corto'>Nombre Corto</label></td> ";
                echo "<td  class='field'><input name ='corto' value = '$corto'  class = 'requer'/>
                <span class='req'>*</span></td>";
				echo "<td ><label for 'porccomision'>Porcentaje de Comisi&oacuten</label></td> ";
                echo "<td class='field' ><input name ='porccomision' value = '$porccomision'  class = 'reqnum'/>
                <span class='req'>*</span></td>";
           echo "</tr>";
            ?>         
     </tr>
   
     
                      
          </table>  <br />
    <!--------el boton de enviar ------------->

		    <div class= "centraelem">
		
		        <?php
		           echo  "<input type='submit' name ='enviomod' value=$titbot />"
		        ?>
		    </div>        
        </form>
    

	</div>
	
	<div id="footer"></div>


</body>


</html>
