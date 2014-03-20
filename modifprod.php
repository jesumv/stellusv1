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
        
// query de seleccion de combo proveedores
            $query="SELECT idproveedores,nom_corto FROM proveedores ORDER BY nom_corto";
            $result1 = mysqli_query ($mysqli,$query) or die("error en consulta de combo");
		
	function oprimio($mysqli,$numid){
		
//esta funcion hace las consultas de actualizacion
		$table = 'productos';
	    $desc =strtoupper($_POST ['desc']) ;
		$corto =strtoupper($_POST ['corto']) ;
		$unidad=strtoupper($_POST ['unidad']) ;
		$precio1=strtoupper($_POST ['precio1']) ;
	    $precio2 =strtoupper($_POST ['precio2']) ;
		$precio3 =strtoupper($_POST ['precio3']) ;
	    $precio4=strtoupper($_POST ['precio4']) ;
		$precio5=strtoupper($_POST ['precio5']) ;
		$precio6=strtoupper($_POST ['precio6']) ;
		$preciost=strtoupper($_POST ['preciost']) ;
		$codigo=strtoupper($_POST ['codigo']) ;
	    $usu = $_SESSION['login_user'];
        $proveedor = $_POST['combo'];

	 
	    if (!is_numeric($numid)) {
	        $sqlCommand= "INSERT INTO $table (descripcion,nom_corto,unidad,precio1,precio2,precio3,precio4,precio5,precio6,preciost,codigo,usu,status,idproveedores)
	        VALUES ('$desc','$corto','$unidad',$precio1,$precio2,$precio3,$precio4,$precio5,$precio6,$preciost,'$codigo','$usu',0,$proveedor)"
	        or die('insercion cancelada '.$table);
			
	    }else {
	        $sqlCommand = "UPDATE $table SET descripcion ='$desc', nom_corto = '$corto', unidad='$unidad',precio1='$precio1',precio2='$precio2',
	         precio3= $precio3, precio4=$precio4, precio5=$precio5,precio6=$precio6,preciost=$preciost,codigo='$codigo',usu = '$usu',status = 1, idproveedores = $proveedor  WHERE idproductos= $numid LIMIT 1"
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
    header('Location: productos.php');
}
	
/***obtiene los datos de acuerdo con el parametro recibido en la pagina***/
        if(isset($_GET['nid'])){
            if($_GET['nid']== -99){
            	$num= "";
                $desc= "";
				$corto = "";
                $unidad = "";
                $precio1="";
                $precio2="";
                $precio3 = "";
                $precio4= "";
				$codigo="";
				$precio5= "";
				$precio6= "";
				$preciost= "";
				$proveedor="";
                //titulo del boton de la forma
                $titbot = "Insertar";
                
            }else{
                $num=$_GET['nid'];
                $sqlsresul= $funcbase->leetodos($mysqli,'productos','idproductos= '."$num.");
                $num = $sqlsresul[0];
				$desc = $sqlsresul[1];
				$corto = $sqlsresul[2];
				$unidad = $sqlsresul[3];
				$precio1 = $sqlsresul[4];
				$precio2= $sqlsresul[5];
				$precio3=$sqlsresul[6];
				$precio4 = $sqlsresul[7];
				$codigo = $sqlsresul[8];
                $precio5 = $sqlsresul[14];
				$precio6 = $sqlsresul[15];
				$preciost = $sqlsresul[16];
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
<link rel="stylesheet" href="css/cupertino/jquery-ui-1.10.4.custom.css">
<link rel="shortcut icon" href="img/logomin.gif" />
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>

<title>STELLUS MEDEVICES</title>
  <script>
   $( document ).ready(function() {
       $('#inic').focus();
});

  </script>
</head>

<body

<!--LISTON DE ENCABEZADO ---------------------------------------------------------------------------------------->  
    <?php 
  $titulo = "ACTUALIZACION PRODUCTOS";
  include_once "include/barrasup.php";
  ?> 

 <!-- la forma. ------>
  <div class="cajacentra">

    <form id="modifprod" action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
        
       <table  class="db-table">
             
     <tr>
            <tr>
                <td >No.</td> 
                <?php
                echo "<input type='hidden' id='num' name ='num' value = $num />";
                echo "<td>$num</td>";
                echo "<td>Descripcion</td>";
				echo "<td><input id='inic' name ='desc' value = '$desc'  size = '60'/> </td>";
				echo "<td>Nombre Corto</td>";
				echo "<td><input name ='corto' value = '$corto'  size = '30'/> </td>";
				echo "<td>Proveedor</td>";
				 /**el combo de cliente**/
               echo "<td><select name= 'combo' >";
        /**printing the list box select command**/
        
        
                    while($nt=mysqli_fetch_row($result1)){
                    //Array or records stored in $nt
                    echo "<option value='$nt[0]'>$nt[1]</option>";
                    }   
                echo "</select></td>";
     echo "</tr>";
            echo "<tr>";
				echo "<td >Unidad:</td> ";
                echo "<td><input  name ='unidad' value = '$unidad'  size = '30'/> </td>";
                echo "<td >Precio 1</td> ";
                echo "<td ><input name ='precio1' value = '$precio1' /></td>";
				echo "<td >Precio 2</td> ";
                echo "<td ><input name ='precio2' value = '$precio2' /></td>";
                echo " <td>Precio 3</td>";
                echo "<td ><input name ='precio3' value = '$precio3' /></td>";
           echo "</tr>";
		   echo "<tr>";
		   		echo "<td> Precio 4</td>";
                echo "<td ><input name ='precio4' value = '$precio4'/></td>";
				echo "<td> Precio 5</td>";
                echo "<td ><input name ='precio5' value = '$precio5'/></td>";
				echo "<td> Precio 6</td>";
                echo "<td ><input name ='precio6' value = '$precio6'/></td>";
				echo "<td> Precio ST</td>";
                echo "<td ><input name ='preciost' value = '$preciost'/></td>";
				echo "</tr>"; 
				echo "<tr>";
				echo "<td>Codigo</td>";
                echo "<td><input name ='codigo' value = '$codigo'/></td>";
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
