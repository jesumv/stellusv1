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
   $usu = $_SESSION['username'];
   $num = $_GET['nid'];
   $table = 'productos';
   $sql= "UPDATE productos SET status = 2, usu = '$usu' WHERE idproductos=-".$num;
   $result2 = mysqli_query($mysqli,$sql) or die('no hay resultados para '.$table);
   
    /* liberar la serie de resultados */
  mysqli_free_result($result2);
  /* cerrar la conexión */
  mysqli_close($mysqli); 
  
  header('Location: productos.php');

?>