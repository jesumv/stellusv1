<?php
    //directiva de la conexion a la base de datos
include_once "php/config.php";

//directiva a la revision de conexion
include_once"php/lock.php";

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