<?php
global $num;

//Este script administra lo actualización a un cliente.
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
		$table = 'clientes';
//esta funcion hace las consultas de actualizacion
	 	$nivel =strtoupper($_POST ['nivel']) ;
	    $nombre =strtoupper($_POST ['nom']) ;
		$rfc=strtoupper($_POST ['rfc']) ;
		$suc=strtoupper($_POST ['suc']) ;
	    $corto =strtoupper($_POST ['corto']) ;
		$calleno =strtoupper($_POST ['calleno']) ;
	    $col=strtoupper($_POST ['col']) ;
		$del=strtoupper($_POST ['del']) ;
		$ciudad=strtoupper($_POST ['ciudad']) ;
		$estado=strtoupper($_POST ['estado']) ;
		$cp=strtoupper($_POST ['cp']) ;
		
	    $usu = $_SESSION['username'];

	 
	    if (!is_numeric($numid)) {
	        $sqlCommand= "INSERT INTO $table (razon_social,rfc,sucursal,nom_corto,calleno,col,del,ciudad,estado,cp,nivel,usu,status)
	        VALUES ('$nombre','$rfc','$suc','$corto','$calleno','$col','$del','$ciudad','$estado','$cp','$nivel','$usu',0)"
	        or die('insercion cancelada '.$table);
			
	    }else {
	        $sqlCommand = "UPDATE $table SET razon_social ='$nombre', rfc='$rfc',sucursal='$suc',nom_corto='$corto',
	         calleno= '$calleno', col='$col',del='$del',ciudad='$ciudad',estado='$estado',cp='$cp',nivel='$nivel',usu = '$usu',status = 1 
	         WHERE idclientes= $numid LIMIT 1"
	         or die('actualizacion cancelada '.$table);
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
    header('Location: clientes.php');
}
	
/***obtiene los datos de acuerdo con el parametro recibido en la pagina***/
        if(isset($_GET['nid'])){
            if($_GET['nid']== -99){
            	$nivel= "";
                $num = "";
                $nombre = "";
                $rfc="";
                $suc="";
                $corto = "";
                $calleno= "";
				$col="";
				$del="";
				$ciudad = "";
				$estado="";
				$cp="";
				
                //titulo del boton de la forma
                $titbot = "Insertar";
                
            }else{
                $num=$_GET['nid'];
                $sqlsresul= $funcbase->leetodos($mysqli,'clientes','idclientes= '."$num.");
                $num = $sqlsresul[0];
                $nombre = $sqlsresul[1];
				$rfc= $sqlsresul[2];
                $suc= $sqlsresul[3];
                $corto= $sqlsresul[4];
				$calleno= $sqlsresul[5];
				$col=$sqlsresul[6];
				$del=$sqlsresul[7];
				$ciudad=$sqlsresul[8];
				$estado = $sqlsresul[9];
				$cp=$sqlsresul[10];
				$nivel = $sqlsresul[11];
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
<title>STELLUS MEDEVICES</title>

<script src="js/jquery-1.11.0.js"></script>
  <script>
  $( document ).ready(function() {
       $('#inic').focus();  
});
  </script>
</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "ACTUALIZACION CLIENTES";
  include_once "include/barrasup.php";
  ?> 

 <!-- la forma. ------>
  <div class="cajacentra">

    <form id="modifcte" action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
        
       <table  class="db-table">
          
        
     <tr>
            <tr>
                <td >No.</td> 
                <?php
                echo "<input type='hidden' id='num' name ='num' value = $num size = '60'/>";
                echo "<td>$num</td>";
                echo "<td>NIVEL</td>";
                echo "<td >
                        <select name ='nivel' value = '$nivel'>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                        </select>
                      </td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td >NOMBRE O RAZON SOCIAL:</td> ";
                echo "<td><input id='inic' name ='nom' value = '$nombre'  size = '60'/> </td>";
                echo "<td >RFC</td> ";
                echo "<td ><input name ='rfc' value = '$rfc' /></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td >SUCURSAL</td> ";
                echo "<td ><input name ='suc' value = '$suc' /></td>";
                echo " <td>NOMBRE CORTO</td>";
                echo "<td ><input name ='corto' value = '$corto' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td> CALLE Y NO.</td>";
                echo "<td ><input name ='calleno' value = '$calleno' size = '60' /></td>";
                echo "<td>COLONIA</td>";
                echo "<td ><input name ='col' value = '$col' size = '60' /></td>";
            echo "</tr>";
			echo "<tr>";
                echo "<td>DELEGACION</td>";
                echo "<td ><input name ='del' value = '$del' size = '60' /></td>";
                echo "<td>CIUDAD</td>";
                echo "<td ><input name ='ciudad' value = '$ciudad' size = '60' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>ESTADO</td>";
                echo "<td ><input name ='estado' value = '$estado' size = '60' /></td>";
                echo "<td>CP</td>";
                echo "<td ><input name ='cp' value = '$cp' size = '10' /></td>";
            echo "</tr>";
             
            ?>         
     </tr>
   
     
                      
          </table>  <br />
    <!--------el boton de enviar ------------->
    <div>
        <?php
           echo  "<input type='submit' name ='enviomod' value=$titbot />"
        ?>
    </div>        
        </form>
    

</div>

<div id="footer"></div>


</body>


</html>
