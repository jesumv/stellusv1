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

//consulta a la base de datos
   $usu = $_SESSION['login_user'];
   $num = $_GET['nid'];
 //cambio de estado de la sucursal
   $table = 'succliente';
   $sql= "UPDATE $table SET status = 2, usu = '$usu' WHERE idsuccliente=-".$num;
   $result2 = mysqli_query($mysqli,$sql) or die('no hay resultados para '.$table);
 //cambio de estado del almacen falta por desarrollar
 
    /* liberar la serie de resultados */
  mysqli_free_result($result2);
  /* cerrar la conexión */
  mysqli_close($mysqli); 
  
  header('Location: sucursales.php');

?>